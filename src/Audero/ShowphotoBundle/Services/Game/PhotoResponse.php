<?php

namespace Audero\ShowphotoBundle\Services\Game;

use Audero\BackendBundle\Entity\Options;
use Audero\ShowphotoBundle\Form\PhotoResponseType;
use Audero\ShowphotoBundle\Services\Uploader\Imgur;
use Symfony\Component\Form\FormFactory;
use Symfony\Component\HttpFoundation\Request;
use Audero\ShowphotoBundle\Entity\PhotoRequest;
use Audero\ShowphotoBundle\Entity\PhotoResponse as Response;
use Audero\ShowphotoBundle\Entity\Wish;
use Cocur\Slugify\Slugify;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\Validator\Constraints\DateTime;

class PhotoResponse
{

    private $em;

    private $factory;

    private $uploader;

    private $security;

    public function __construct(EntityManager $em, FormFactory $factory, Imgur $uploader, SecurityContext $security)
    {
        $this->em = $em;
        $this->factory = $factory;
        $this->uploader = $uploader;
        $this->security = $security;
    }

    public function handlePhotoResponse(Request $request)
    {
        if (!($user = $this->security->getToken()->getUser())) {
            throw new \Exception('User was not found');
        }

        $response = new Response();
        $form = $this->factory->create(new PhotoResponseType(), $response);
        $form->handleRequest($request);
        if (!$form->isValid()) {
            throw new \Exception($form->getErrors());
        }

        // checking time
        /** @var PhotoRequest $photoRequest */
        $photoRequest = $this->em->getRepository("AuderoShowphotoBundle:PhotoRequest")->findNewest();
        if (!$photoRequest) {
            throw new \Exception('No Request was found');
        }

        $validUntil = $this->getValidUntil($photoRequest);
        if (time() > $validUntil->getTimestamp()) {
            throw new \Exception('Request time has expired');
        }

        if ($response->getPhotoUrl()) {
            $data = $this->uploader->uploadUrl($response->getPhotoUrl());
        } else {
            $data = $this->uploader->uploadFile($response->getPhotoFile());
        }

        if ($data && isset($data->status) && $data->status == 200) {
            $data = $data->data;
            $response->setUser($user)
                ->setAnimated($data->animated)
                ->setDeleteHash($data->deletehash)
                ->setHeight($data->height)
                ->setPhotoId($data->id)
                ->setPhotoLink($data->link)
                ->setRequest($photoRequest)
                ->setSize($data->size)
                ->setWidth($data->width);

            return $response;
        }

        throw new \Exception('Response from image storage is not valid');
    }


    public function broadcast(Response $response)
    {
        $data = array(
            'command' => 'push',
            'data' => array(
                'topic' => "game_request",
                'data' => array(
                    'request' => $response->getRequest()->getTitle(),
                    'user' => $response->getUser()->getUsername(),
                    'validUntil' => $this->getValidUntil($response->getRequest()),
                )
            )
        );

        $context = new \ZMQContext();
        $socket = $context->getSocket(\ZMQ::SOCKET_PUSH, 'pusher');
        $socket->connect("tcp://127.0.0.1:5555");
        $socket->send(json_encode($data));
    }

    private function getValidUntil(PhotoRequest $request)
    {
        /** @var Options $options */
        $options = $this->em->getRepository("AuderoBackendBundle:OptionsRecord")->findCurrent();
        if (!$options) {
            return null;
        }

        return $request->getDate()->add(new \DateInterval('PT' . $options->getTimeForResponse() . 'S'));
    }
} 
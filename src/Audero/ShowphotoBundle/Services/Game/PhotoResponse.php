<?php

namespace Audero\ShowphotoBundle\Services\Game;

use Audero\BackendBundle\Entity\Options;
use Audero\ShowphotoBundle\Form\PhotoResponseType;
use Audero\ShowphotoBundle\Services\Uploader\Imgur;
use Audero\WebBundle\Services\Pusher\PusherQueue;
use Symfony\Component\Form\FormFactory;
use Symfony\Component\HttpFoundation\Request;
use Audero\ShowphotoBundle\Entity\PhotoRequest;
use Audero\ShowphotoBundle\Entity\PhotoResponse as Response;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Security\Core\SecurityContext;

/**
 * Class PhotoResponse
 * @package Audero\ShowphotoBundle\Services\Game
 */
class PhotoResponse
{
    /**
     * @var EntityManager
     */
    private $em;

    /**
     * @var FormFactory
     */
    private $factory;

    /**
     * @var Imgur
     */
    private $uploader;

    /**
     * @var SecurityContext
     */
    private $security;

    /**
     * @var PusherQueue
     */
    private $pQueue;

    /**
     * @param EntityManager $em
     * @param FormFactory $factory
     * @param Imgur $uploader
     * @param SecurityContext $security
     * @param PusherQueue $pQueue
     */
    public function __construct(EntityManager $em, FormFactory $factory, Imgur $uploader, SecurityContext $security, PusherQueue $pQueue)
    {
        $this->em = $em;
        $this->factory = $factory;
        $this->uploader = $uploader;
        $this->security = $security;
        $this->pQueue = $pQueue;
    }

    /**
     * @param Request $request
     * @return Response
     * @throws \Exception
     */
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

    /**
     * @param Response $response
     */
    public function broadcast(Response $response)
    {
        $data = array(
            'command' => 'push',
            'data' => array(
                'topic' => "game_response",
                'data' => array(
                    'photoLink' => $response->getPhotoLink(),
                    'username' => $response->getUser()->getUsername(),
                    'id' => $response->getId(),
                )
            )
        );

        $this->pQueue->add($data);
    }

    /**
     * @param PhotoRequest $request
     * @return \DateTime|null
     */
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
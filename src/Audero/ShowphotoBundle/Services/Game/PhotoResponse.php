<?php

namespace Audero\ShowphotoBundle\Services\Game;
use Audero\ShowphotoBundle\Form\PhotoResponseType;
use Audero\ShowphotoBundle\Services\Uploader\Imgur;
use Audero\WebBundle\Services\Pusher\PusherQueue;
use Symfony\Component\Form\FormFactory;
use Symfony\Component\HttpFoundation\Request;
use Audero\ShowphotoBundle\Services\Game\PhotoRequest as PRequestService;
use Audero\ShowphotoBundle\Entity\PhotoResponse as PResponseEntity;
use Audero\ShowphotoBundle\Entity\PhotoRequest as PRequestEntity;
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
     * @var PhotoRequest
     */
    private $pRequestService;

    /**
     * @var PusherQueue
     */
    private $pQueue;

    /**
     * @param EntityManager $em
     * @param FormFactory $factory
     * @param Imgur $uploader
     * @param SecurityContext $security
     * @param PRequestService $pRequestService
     * @param PusherQueue $pusherQueue
     */
    public function __construct(EntityManager $em, FormFactory $factory, Imgur $uploader, SecurityContext $security, PRequestService $pRequestService, PusherQueue $pusherQueue)
    {
        $this->em = $em;
        $this->factory = $factory;
        $this->uploader = $uploader;
        $this->security = $security;
        $this->pRequestService = $pRequestService;
        $this->pusherQueue = $pusherQueue;
    }

    public function handlePhotoResponse(Request $request)
    {
        if (!($user = $this->security->getToken()->getUser())) {
            throw new \Exception('User was not found');
        }

        $pResponseEntity = new pResponseEntity();
        $form = $this->factory->create(new PhotoResponseType(), $pResponseEntity);
        $form->handleRequest($request);
        if (!$form->isValid()) {
            throw new \Exception($form->getErrors());
        }

        /** @var PRequestEntity $pRequestEntity */
        $pRequestEntity = $this->em->getRepository("AuderoShowphotoBundle:PhotoRequest")->findNewest();
        if (!$pRequestEntity) {
            throw new \Exception('No Request was found');
        }

        $validUntil = $this->pRequestService->getValidUntil($pRequestEntity);
        if (time() > $validUntil) {
            throw new \Exception('Request time has expired');
        }

        if ($pResponseEntity->getPhotoUrl()) {
            $data = $this->uploader->uploadUrl($pResponseEntity->getPhotoUrl());
        } else {
            $data = $this->uploader->uploadFile($pResponseEntity->getPhotoFile());
        }

        if ($data && isset($data->status) && $data->status == 200) {
            $data = $data->data;
            $pResponseEntity->setUser($user)
                            ->setAnimated($data->animated)
                            ->setDeleteHash($data->deletehash)
                            ->setHeight($data->height)
                            ->setPhotoId($data->id)
                            ->setPhotoLink($data->link)
                            ->setRequest($pRequestEntity)
                            ->setSize($data->size)
                            ->setWidth($data->width);

            return $pResponseEntity;
        }

        throw new \Exception('Response from image storage is not valid');
    }

    /**
     * @param pResponseEntity $pResponseEntity
     */
    public function broadcast(pResponseEntity $pResponseEntity)
    {
        $data = array(
            'command' => 'push',
            'data' => array(
                'topic' => "game_response",
                'data' => array(
                    'photoLink' => $pResponseEntity->getPhotoLink(),
                    'username' => $pResponseEntity->getUser()->getUsername(),
                    'id' => $pResponseEntity->getId(),
                )
            )
        );

        $this->pQueue->add($data);
    }
} 
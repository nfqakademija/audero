<?php

namespace Audero\ShowphotoBundle\Services\Game;

use Audero\ShowphotoBundle\Services\Uploader\Imgur;
use Audero\WebBundle\Services\Pusher\PusherQueue;
use Symfony\Component\Form\FormFactory;
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
    private $pusherQueue;

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

    /**
     * @param PResponseEntity $response
     * @return PResponseEntity
     * @throws \Exception
     */
    public function manage(PResponseEntity $response)
    {
        if (!($user = $this->security->getToken()->getUser())) {
            throw new \Exception('User could not be found');
        }

        /** @var PRequestEntity $pRequestEntity */
        $request = $this->em->getRepository("AuderoShowphotoBundle:PhotoRequest")->findLastBroadcasted();
        if (!($request instanceof PRequestEntity)) {
            throw new \Exception('No Request was found');
        }

        $storedResponse = $this->em->getRepository("AuderoShowphotoBundle:PhotoResponse")->findOneBy(array('request' => $request, "user" => $user));
        if ($storedResponse) {
            throw new \Exception('You have already responded to this request');
        }

        $validUntil = $request->getValidUntil();
        if(!$validUntil) {
            throw new \Exception('Could not get validUntil value from photoRequest Entity');
        }

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
                ->setRequest($request)
                ->setSize($data->size)
                ->setWidth($data->width);
            return $response;
        }

        throw new \Exception("Failed to upload image. Try again or upload from pc \n ");
    }

    /**
     * @param PResponseEntity $response
     */
    public function broadcast(PResponseEntity $response)
    {
        $data = array(
            'topic' => 'game',
            'data' => array(
                'type' => 'response',
                'photoLink' => $response->getPhotoLink(),
                'requestSlug' => $response->getRequest()->getSlug(),
                'author' => $response->getUser()->getUsername(),
            )
        );

        $this->pusherQueue->add($data);
    }
} 
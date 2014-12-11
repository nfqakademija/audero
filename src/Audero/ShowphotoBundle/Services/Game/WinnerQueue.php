<?php

namespace Audero\ShowphotoBundle\Services\Game;

use Audero\ShowphotoBundle\Entity\PhotoRequest as PRequestEntity;
use Audero\WebBundle\Services\Pusher\PusherQueue;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManager;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class WinnerQueue
 * @package Audero\ShowphotoBundle\Services\Game
 */
class WinnerQueue {

    /**
     * @var EntityManager
     */
    private $em;

    /**
     * @var PusherQueue
     */
    private $pusherQueue;

    /**
     * @param EntityManager $em
     * @param PusherQueue $pusherQueue
     */
    public function __construct(EntityManager $em, PusherQueue $pusherQueue) {
        $this->em = $em;
        $this->pusherQueue = $pusherQueue;
    }

    /**
     * @param PRequestEntity $request
     * @return ArrayCollection
     */
    public function generate(PRequestEntity $request) {
        return $this->em->getRepository("AuderoShowphotoBundle:Player")->findWinnerQueue($request);
    }

    /**
     * @param PRequestEntity $request
     * @param $timeToShow
     */
    public function broadcast(PRequestEntity $request, $timeToShow) {
        $winnerQueue = (array) $this->generate($request);

        if(!$winnerQueue) {
            return null;
        }

        $playersData = array(); $place = 0;
        foreach($winnerQueue as $entity) {
            $place++;
            $playersData[] = array(
                'place'    => $place,
                'username' => $entity['username'],
                'userRate' => $entity['rate'],
                'likes'    => $entity['likes'],
                'dislikes' => $entity['dislikes'],
                'responseRate' => $entity['resRate']
            );
        }

        $data = array(
            'topic' => 'game',
            'data'  => array(
                'type' => 'winnerQueue',
                'timeToShow' => $timeToShow,
                'playersData' => $playersData
            )
        );

        $this->pusherQueue->add($data);
    }

}
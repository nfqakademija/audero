<?php

namespace Audero\ShowphotoBundle\Services\Game;

use Audero\ShowphotoBundle\Entity\User;
use Audero\ShowphotoBundle\Entity\Wish as WishEntity;
use Audero\WebBundle\Services\Pusher\PusherQueue;
use Doctrine\ORM\EntityManager;


/**
 * Class Wish
 * @package Audero\ShowphotoBundle\Services\Game
 */
class Wish {

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
     * @param User $user
     * @throws \Exception
     */
    public function broadcast(User $user) {
        /** @var \Audero\BackendBundle\Entity\Options $options */
        $options = $this->em->getRepository('AuderoBackendBundle:OptionsRecord')->findCurrent();
        if(!$options) {
            throw new \Exception('Could not get backend options');
        }

        $wishesRepo = $this->em->getRepository("AuderoShowphotoBundle:Wish");
        $wishes = (array) $wishesRepo->findOrderedByPosition($user, $options->getPlayerWishesCount());
        $wishList = array();
        /**@var WishEntity $wish*/
        foreach($wishes as $wish) {
            $wishList[$wish->getPosition()] = $wish->getTitle();
        }

        $data = array(
            'topic' => 'game',
            'username' => $user->getUsername(),
            'data'  => array(
                'type' => 'wish',
                'wishList' => $wishList,
            )
        );

        $this->pusherQueue->add($data);
    }

}
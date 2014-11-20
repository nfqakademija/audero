<?php

namespace Audero\ShowphotoBundle\Services\Game;

use Audero\ShowphotoBundle\Entity\Player;
use Audero\ShowphotoBundle\Entity\User;
use Doctrine\ORM\EntityManager;

class PlayerManager {

    private $em;

    public function __construct(EntityManager $em) {
        $this->em = $em;
    }

    public function add(User $user) {
        $options = $this->em->getRepository("AuderoBackendBundle:OptionsRecord")->findCurrent();
        $playersRepo = $this->em->getRepository("AuderoShowphotoBundle:Player");

        // checking if player is already checked in
        if($player = $playersRepo->findOneBy(array('user'=>$user->getId()))) {
            return $player;
        }

        // adding user to players list
        if($options) {
           $maxPlayers = $options->getPlayersInOneRoom();
           if($playersRepo->getPlayersCount() < $maxPlayers) {
               $player = new Player();
               $player->setUser($user);
               $this->em->persist($player);
               $this->em->flush();
               return $player;
           }
        }

        return null;
    }

    public function remove(User $user) {

    }

    public function getFreeSlots() {

    }

    public function getPlayersCount() {

    }

} 
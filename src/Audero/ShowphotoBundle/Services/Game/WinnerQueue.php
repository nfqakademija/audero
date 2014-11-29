<?php

namespace Audero\ShowphotoBundle\Services\Game;

use Audero\ShowphotoBundle\Entity\PhotoRequest;
use Doctrine\ORM\EntityManager;
use Symfony\Component\DependencyInjection\ContainerInterface;

class WinnerQueue {

    private $em;
    private $container;

    public function __construct(EntityManager $em, ContainerInterface $container) {
        $this->em = $em;
        $this->container = $container;
    }

    public function generate() {

        $request = $this->em->getRepository("AuderoShowphotoBundle:PhotoRequest")->findLast();
        if($request) {

        }
        return $this->container->get('fos_user.user_manager')->findUsers();
    }

}
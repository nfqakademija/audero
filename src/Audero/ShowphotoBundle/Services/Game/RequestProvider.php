<?php

namespace Audero\ShowphotoBundle\Services\Game;

use Audero\ShowphotoBundle\Entity\PhotoRequest;
use Doctrine\ORM\EntityManager;

class RequestProvider {

    private $em;

    public function __construct(EntityManager $em) {
        $this->em = $em;
    }

    public function generate() {
        $request = new PhotoRequest();
        $request->setTitle("naujo automobilio");
        return $request;
    }

    public function store() {

    }

} 
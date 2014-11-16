<?php

namespace Audero\WebBundle\Services;

use Doctrine\ORM\EntityManager;
use Ratchet\ConnectionInterface;

class SocketConnectionManager {

    private $em;

    public function  __construct(EntityManager $em) {
        $this->em = $em;
    }

    public function addSubscriber(ConnectionInterface $conn, $topic) {
        return true;
    }

} 
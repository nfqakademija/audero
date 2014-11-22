<?php

namespace Audero\WebBundle\Services;

use Doctrine\ORM\EntityManager;
use Ratchet\ConnectionInterface;

class SocketConnectionManager {

    private $em;
    private $availableTopics = array('game_request', 'game_response', 'chat');

    public function  __construct(EntityManager $em) {
        $this->em = $em;
    }

    public function hasPermissions(ConnectionInterface $conn, $topic) {
        // checking if topic is available
        if(!in_array($topic->getId(), $this->availableTopics)) {
            return false;
        }

        // checking if user has right permissions
        return true;
    }

} 
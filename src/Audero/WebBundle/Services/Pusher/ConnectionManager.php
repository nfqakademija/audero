<?php

namespace Audero\WebBundle\Services\Pusher;

use Audero\ShowphotoBundle\Entity\User;
use Doctrine\ORM\EntityManager;
use Ratchet\ConnectionInterface;

class ConnectionManager {

    private $em;
    private $availableTopics = array('game_request', 'game_response', 'chat');
    protected $connections;

    public function  __construct(EntityManager $em) {
        $this->em = $em;
        $this->connections = new \SplObjectStorage;
    }

    public function hasPermissions(ConnectionInterface $conn, $topic) {
        // checking if topic is available
        if(!in_array($topic->getId(), $this->availableTopics)) {
            return false;
        }

        // checking if user has right permissions
        return true;
    }

    public function setUserConnId(User $userSession, $id) {
        if(is_object($userSession) && is_int($id)) {
            $user = $this->em->getRepository("AuderoShowphotoBundle:User")->findOneBy(array('id'=>$userSession  ->getId()));
            $user->setConnId($id);
            $this->em->persist($user);
            $this->em->flush();
            return $user;
        }

        return null;
    }

} 
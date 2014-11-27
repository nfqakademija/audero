<?php

namespace Audero\WebBundle\Services\Pusher;

use Audero\WebBundle\Entity\UserConnection;
use Audero\WebBundle\Entity\UserSubscription;
use Doctrine\ORM\EntityManager;
use Ratchet\ConnectionInterface;
use Ratchet\Wamp\Topic;

class ConnectionManager {

    private $em;
    private $topics;
    protected $connections = array();

    public function  __construct(EntityManager $em) {
        $this->em = $em;
        $this->topics = array(
            'game_request'=> new Topic('game_request'),
            'game_response' => new Topic('game_response'),
            'chat' => new Topic('chat')
        );

        $this->clearConnectionsFromDatabase();
    }

    public function hasPermissions(ConnectionInterface $conn, Topic $topic =null) {
        return true;
    }

    public function isAvailable(Topic $topic) {
        if(array_key_exists($topic->getId(), $this->topics)) {
            return $this->topics[$topic->getId()];
        }

        return null;
    }

    public function getTopicById($id) {
        if(is_string($id)) {
            return array_key_exists($id, $this->topics) ? $this->topics[$id] : null;
        }

        return null;
    }

    public function addConnection(ConnectionInterface $conn) {
        $userConnection = new UserConnection();
        $userConnection->setUser($this->extractUserFromSession($conn))
                       ->setResourceId($conn->resourceId);

        $this->store($userConnection);
        return $userConnection;
    }

    /* *
     * Removing connection from all the topics
     * Removing connection from database
     * Closing connection
     * */
    public function removeConnection(ConnectionInterface $conn) {
        foreach($this->topics as $id => $topic) {
            $topic->remove($conn);
        }

        $connection = $this->em->getRepository("AuderoWebBundle:UserConnection")->findOneBy(array('resourceId'=>$conn->resourceId));
        if($connection) {
            $this->em->remove($connection);
            $this->em->flush();
        }

        $conn->close();
    }

    public function addSubscriber(ConnectionInterface $conn, Topic $topic) {
        $topic = $this->isAvailable($topic);
        if(!$topic) {
            return null;
        }

        $storedConn = $this->em->getRepository("AuderoWebBundle:UserConnection")->findOneBy(array('resourceId' => $conn->resourceId));
        if(!$storedConn) {
            return null;
        }

        // adding connection to requested topic
        $topic->add($conn);
        // setting new subscription entity
        $userSubscription = new UserSubscription();
        $userSubscription->setConnection($storedConn)
                         ->setTopic($topic->getId());
        // TODO FIX REMOVE CASCADE
        $this->store($userSubscription);

        return $userSubscription;
    }

    private function store($entity) {
        $this->em->persist($entity);
        $this->em->flush();
    }

    public function clearConnectionsFromDatabase() {
        $this->em->createQuery('DELETE FROM AuderoWebBundle:UserConnection conn')
             ->execute();
    }



    /* *
     * Removing connection from topic's connections list
     * Removing from database
     * */
    public function removeSubscription(ConnectionInterface $conn, Topic $topic) {
        $topic = $this->isAvailable($topic);
        if(!$topic) {
            return null;
        }

        $topic->remove($conn);

        $connection = $this->em->getRepository("AuderoWebBundle:UserConnection")->findOneBy(array('resourceId'=>$conn->resourceId));
        if(!$connection) {
            return null;
        }
        $subscription = $this->em->getRepository("AuderoWebBundle:UserSubscription")->findOneBy(array('connection'=>$connection, 'topic'=>$topic->getId()));
        if($subscription) {
            $this->em->remove($subscription);
            $this->em->flush();
        }
    }

    private function extractUserFromSession(ConnectionInterface $conn) {
        $security_main = unserialize($conn->Session->get('_security_main'));
        if($security_main) {
            if(!$security_main->getUser()) {
                return null;
            }

            $id = $security_main->getUser()->getId();
            if(!$id) {
                return null;
            }

            return $this->em->getRepository("AuderoShowphotoBundle:User")->findOneBy(array('id'=>$id));
        }

        return null;
    }

}
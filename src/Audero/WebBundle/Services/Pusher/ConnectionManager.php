<?php

namespace Audero\WebBundle\Services\Pusher;

use Audero\WebBundle\Entity\UserConnection;
use Audero\WebBundle\Entity\UserSubscription;
use Doctrine\ORM\EntityManager;
use Ratchet\ConnectionInterface;
use Ratchet\Wamp\Topic;
use Symfony\Component\Config\Definition\Exception\Exception;

/**
 * Class ConnectionManager
 * @package Audero\WebBundle\Services\Pusher
 */
class ConnectionManager {

    /**
     * @var EntityManager
     */
    private $em;

    /**
     * @var array
     */
    private $topics;

    /**
     * @var array
     */
    protected $connections = array();

    /**
     * @param EntityManager $em
     *
     *
     */

    public function  __construct(EntityManager $em) {
        $this->em = $em;
        $this->topics = array(
            'game_request'=> new Topic('game_request'),
            'game_response' => new Topic('game_response'),
            'chat' => new Topic('chat')
        );

        $this->clearConnectionsFromDatabase();
    }

    /**
     * @param ConnectionInterface $conn
     * @param Topic $topic
     * @return bool
     */
    public function hasPermissions(ConnectionInterface $conn, Topic $topic =null) {
        return true;
    }

    /**
     * @param $id
     * @return null|Topic $topic
     */
    public function getTopicById($id) {
        if(is_string($id)) {
            return array_key_exists($id, $this->topics) ? $this->topics[$id] : null;
        }

        return null;
    }

    /**
     * @param ConnectionInterface $conn
     * @return null|UserConnection
     */
    public function addConnection(ConnectionInterface $conn) {
        // check if one is already stored

        $userConnection = new UserConnection();
        $userConnection->setResourceId($conn->resourceId);

        $userSession = $this->extractUserFromSession($conn);
        if($userSession){
            $user = $this->em->getRepository("AuderoShowphotoBundle:user")->findOneBy(array('id'=>$userSession->getId()));
            if($user){
                $this->em->persist($user);
                $userConnection->setUser($user);
            }
        }

        try{
            $this->em->persist($userConnection);
            $this->em->flush();
        }catch (\Exception $e) {
            var_dump($e->getMessage());
        }

        return $userConnection;
    }

    /* *
     * Removing connection from all the topics
     * Removing connection from database
     * Closing connection
     *
     * @param ConnectionInterface $conn
     * */
    public function removeConnection(ConnectionInterface $conn) {
        foreach($this->topics as $id => $topic) {
            $topic->remove($conn);
        }

        $connection = $this->em->getRepository("AuderoWebBundle:UserConnection")->findOneBy(array('resourceId'=>$conn->resourceId));
        if($connection) {
            var_dump("remove conn id: ".$connection->getResourceId());
            try{
                $this->em->remove($connection);
                $this->em->flush();
            }catch (\Exception $e) {
                return null;
            }

        }

        $conn->close();
    }

    /**
     * @param ConnectionInterface $conn
     * @param Topic $topic
     * @return null|UserSubscription
     */
    public function addSubscriber(ConnectionInterface $conn, Topic $topic) {
        $topic = $this->getTopicById($topic->getId());
        if(!$topic) {
            return null;
        }

        $storedConn = $this->em->getRepository("AuderoWebBundle:UserConnection")->findOneBy(array('resourceId' => $conn->resourceId));
        if(!$storedConn) {
            return null;
        }

        // adding connection to requested topic
        $topic->add($conn);

        // storing new subscription entity
        $userSubscription = new UserSubscription();
        $userSubscription->setConnection($storedConn)
                         ->setTopic($topic->getId());

        $storedConn->addSubscription($userSubscription);
        $this->em->persist($storedConn);
        $this->em->flush();

        return $userSubscription;
    }

    /**
     *  Removing all connections from database
     */
    public function clearConnectionsFromDatabase() {
        $this->em->createQuery('DELETE FROM AuderoWebBundle:UserConnection conn')
             ->execute();
    }

    /* *
     * Removing connection from topic's connections list
     * Removing from database
     * */
/*    public function removeSubscription(ConnectionInterface $conn, Topic $topic) {
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
    }*/

    /**
     * @param ConnectionInterface $conn
     * @return null|object
     */
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
<?php

namespace Audero\WebBundle\Services\Pusher;

use Audero\ShowphotoBundle\Entity\User;
use Audero\WebBundle\Entity\UserConnection;
use Audero\WebBundle\Entity\UserSubscription;
use Doctrine\ORM\EntityManager;
use Ratchet\ConnectionInterface;
use Ratchet\Wamp\Topic;

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
     */
    public function  __construct(EntityManager $em) {
        $this->em = $em;
        $this->topics = array(
            'game'=> new Topic('game'),
            'chat' => new Topic('chat'),
            'rating' => new Topic('rating')
        );

        $this->clearConnectionsFromDatabase();
    }

    /**
     * @param ConnectionInterface $conn
     * @param Topic $topic
     * @return bool
     */
    public function hasPermissions(ConnectionInterface $conn, Topic $topic=null) {
        /*For this moment*/
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
     * @return UserConnection
     * @throws \Exception
     */
    public function addConnection(ConnectionInterface $conn) {
        /**@var User $user*/
        $user = $this->extractUserFromSession($conn);

        $userConnection = new UserConnection();
        $userConnection->setResourceId($conn->resourceId)
                        ->setUser($user)
                        ->setIp($conn->remoteAddress);
        try{
            $this->em->persist($userConnection);
            $this->em->flush();
        }catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }


        if(!$this->em->getRepository("AuderoWebBundle:UserConnection")->findOneBy(array("resourceId"=>$conn->resourceId, "user"=>$user))) {
            throw new \Exception('Failed to store new connection in database');
        }

        $this->connections[$conn->resourceId] = $conn;

        return $userConnection;
    }

    /**
     * Removing connection from all the topics
     * Removing connection from database
     * Closing connection
     *
     * @param ConnectionInterface $conn
     * @throws \Exception
     */
    public function removeConnection(ConnectionInterface $conn) {
        unset($this->connections[$conn->resourceId]);
        /**@var Topic $topic*/
        foreach($this->topics as $id => $topic) {
            $topic->remove($conn);
        }

        $connection = $this->em->getRepository("AuderoWebBundle:UserConnection")->findOneBy(array('resourceId'=>$conn->resourceId));
        if(!$connection) {
            throw new \Exception("User wanted to disconnect, but no connection was found in database");
        }

        try{
            $this->em->remove($connection);
            $this->em->flush();
        }catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }

        if($this->em->getRepository("AuderoWebBundle:UserConnection")->findOneBy(array('resourceId'=>$conn->resourceId))) {
            throw new \Exception("Failed to delete connection from database");
        }

        $conn->close();
    }

    /**
     * @param ConnectionInterface $conn
     * @param Topic $topic
     * @return UserSubscription
     * @throws \Exception
     */
    public function addSubscription(ConnectionInterface $conn, Topic $topic) {
        $topic = $this->getTopicById($topic->getId());
        if(!$topic) {
            return null;
        }
        /**@var UserConnection $storedConn */
        $storedConn = $this->em->getRepository("AuderoWebBundle:UserConnection")->findOneBy(array('resourceId' => $conn->resourceId));
        if(!$storedConn) {
            throw new \Exception("Connection for user's subscription was not found in database");
        }

        $topic->add($conn);

        $userSubscription = new UserSubscription();
        $userSubscription->setConnection($storedConn)
                         ->setTopic($topic->getId());
        $storedConn->addSubscription($userSubscription);

        try{
            $this->em->persist($storedConn);
            $this->em->flush();
        }catch(\Exception $e) {
            throw new \Exception($e->getMessage());
        }

        if(!$this->em->getRepository("AuderoWebBundle:UserSubscription")->findOneBy(array('topic' => $topic->getId(), 'connection'=>$storedConn))) {
            throw new \Exception("Failed to store user's subscription in database");
        }

        return $userSubscription;
    }

    /**
     *  Removing all connections from database
     */
    public function clearConnectionsFromDatabase() {
        $this->em->createQuery('DELETE FROM AuderoWebBundle:UserConnection conn')
             ->execute();
    }

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
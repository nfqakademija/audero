<?php

namespace Audero\WebBundle\Services\Pusher\Pusher;

use Audero\ShowphotoBundle\Entity\User;
use Audero\ShowphotoBundle\Services\Game\Player;
use Audero\WebBundle\Entity\UserConnection;
use Audero\WebBundle\Entity\UserSubscription;
use Doctrine\ORM\EntityManager;
use Ratchet\ConnectionInterface;
use Ratchet\Wamp\Topic;
use Symfony\Component\CssSelector\Exception\InternalErrorException;

/**
 * Class ConnectionManager
 * @package Audero\WebBundle\Services\Pusher
 */
class ConnectionManager
{

    /**
     * @var EntityManager
     */
    private $em;

    /**
     * @var Player
     */
    private $player;

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
     * @param Player $player
     */
    public function  __construct(EntityManager $em, Player $player)
    {
        $this->em = $em;
        $this->player = $player;
        $this->topics = array(
            'game' => new Topic('game'),
            'chat' => new Topic('chat'),
            'rating' => new Topic('rating')
        );
    }

    /**
     * @param $id
     * @return null|Topic $topic
     */
    public function getTopicById($id)
    {
        if (is_string($id)) {
            return array_key_exists($id, $this->topics) ? $this->topics[$id] : null;
        }

        return null;
    }

    /**
     * @param ConnectionInterface $conn
     * @param Topic $topic
     * @return bool
     */
    public function hasPermissions(ConnectionInterface $conn, Topic $topic = null)
    {
        /*For this moment*/
        return true;
    }

    /**
     * Stores connection to the database and adds to $connections array
     *
     * @param ConnectionInterface $conn
     * @return UserConnection
     * @throws \Exception
     */
    public function addConnection(ConnectionInterface $conn)
    {
        /**@var User $user */
        $user = $this->extractUserFromSession($conn);

        $userConnection = new UserConnection();
        $userConnection->setResourceId($conn->resourceId)
            ->setUser($user)
            ->setIp($conn->remoteAddress);
        try {
            $this->em->persist($userConnection);
            $this->em->flush();
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }


        if (!$this->em->getRepository("AuderoWebBundle:UserConnection")->findOneBy(array("resourceId" => $conn->resourceId, "user" => $user))) {
            throw new \Exception('Failed to store new connection in database');
        }

        $this->connections[$conn->resourceId] = $conn;

        return $userConnection;
    }

    /**
     * Removes connection from all the topics and from database
     * DOES NOT CLOSE CONNECTION ITSELF.
     *
     * @param ConnectionInterface $conn
     * @throws \Exception
     */
    public function removeConnection(ConnectionInterface $conn)
    {
        unset($this->connections[$conn->resourceId]);
        /**@var Topic $topic */
        foreach ($this->topics as $id => $topic) {
            $topic->remove($conn);
        }
        /**@var UserConnection $connection */
        $connection = $this->em->getRepository("AuderoWebBundle:UserConnection")->findOneBy(array('resourceId' => $conn->resourceId));
        if ($connection) {
            try {
                $this->em->remove($connection);
                $this->em->flush();

                /*Notifying Player's service about connection closure*/
                $playerEntity = $this->em->getRepository("AuderoShowphotoBundle:Player")->findOneBy(array('user' => $connection->getUser()));
                if ($playerEntity) {
                    $this->player->hasDisconnected($connection);
                }
            } catch (\Exception $e) {
                throw new \Exception($e->getMessage());
            }
        }

        if ($this->em->getRepository("AuderoWebBundle:UserConnection")->findOneBy(array('resourceId' => $conn->resourceId))) {
            throw new \Exception("Failed to remove connection from database");
        }
    }

    /**
     * Stores subscription to the database and adds
     * to the topic
     *
     * @param ConnectionInterface $conn
     * @param Topic $topic
     * @return UserSubscription
     * @throws \Exception
     */
    public function addSubscription(ConnectionInterface $conn, Topic $topic)
    {
        $topic = $this->getTopicById($topic->getId());
        if (!$topic) {
            return null;
        }
        /**@var UserConnection $storedConn */
        $storedConn = $this->em->getRepository("AuderoWebBundle:UserConnection")->findOneBy(array('resourceId' => $conn->resourceId));
        if (!$storedConn) {
            throw new \Exception("Connection for user's subscription was not found in database");
        }

        $topic->add($conn);

        $userSubscription = new UserSubscription();
        $userSubscription->setConnection($storedConn)
            ->setTopic($topic->getId());
        $storedConn->addSubscription($userSubscription);

        try {
            $this->em->persist($storedConn);
            $this->em->flush();
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }

        if (!$this->em->getRepository("AuderoWebBundle:UserSubscription")->findOneBy(array('topic' => $topic->getId(), 'connection' => $storedConn))) {
            throw new \Exception("Failed to store user's subscription in database");
        }

        return $userSubscription;
    }

    /**
     * Removes all connections from the database
     *
     * @return bool
     */
    public final function clearConnectionsFromDatabase()
    {
        $this->em->createQuery('DELETE FROM AuderoWebBundle:UserConnection conn')
            ->execute();

        return $this->em->getRepository("AuderoWebBundle:UserConnection")->findAll() ? false : true;
    }

    /**
     * Extracts user from session data
     *
     * @param ConnectionInterface $conn
     * @return null|object
     */
    private function extractUserFromSession(ConnectionInterface $conn)
    {
        $security_main = unserialize($conn->Session->get('_security_main'));
        if ($security_main) {
            if (!$security_main->getUser()) {
                return null;
            }

            $id = $security_main->getUser()->getId();
            if (!$id) {
                return null;
            }

            return $this->em->getRepository("AuderoShowphotoBundle:User")->findOneBy(array('id' => $id));
        }

        return null;
    }

    /**
     * @param $username
     * @return null | ConnectionInterface
     * @throws InternalErrorException
     */
    public function getConnectionByUsername($username)
    {

        $user = $this->em->getRepository("AuderoShowphotoBundle:User")->findOneBy(array('username' => $username));
        if (!$user) {
            return null;
        }

        /**@var UserConnection $connection */
        $connection = $this->em->getRepository("AuderoWebBundle:UserConnection")->findOneBy(array('user' => $user));
        if (!$connection) {
            return null;
        }

        if (!isset($this->connections[$connection->getResourceId()])) {
            throw new InternalErrorException();
        }

        return $this->connections[$connection->getResourceId()];
    }

    /**
     * @param $ip
     * @return array
     * @throws InternalErrorException
     */
    public function getConnectionsByIp($ip)
    {
        $connections = $this->em->getRepository("AuderoWebBundle:UserConnection")->findBy(array('ip' => trim($ip)));
        $mappedConnections = array();

        /**@var UserConnection $conn */
        foreach ($connections as $conn) {
            if (!isset($this->connections[$conn->getResourceId()])) {
                throw new InternalErrorException();
            }
            $mappedConnections[$conn->getIp()] = $this->connections[$conn->getResourceId()];

        }

        return $mappedConnections;
    }

    /**
     * Fully closes connection
     *
     * @param $username
     * @return bool
     * @throws \Exception
     */
    public function closeByUsername($username)
    {
        $connection = $this->getConnectionByUsername($username);
        if ($connection) {
            $connection->close();
        }

        return true;
    }

    /**
     * Fully closes connection
     *
     * @param $ip
     * @return bool
     * @throws \Exception
     */
    public function closeByIp($ip)
    {
        $connections = (array)$this->getConnectionsByIp($ip);
        /**@var ConnectionInterface $conn */
        foreach ($connections as $conn) {
            $conn->close();
        }

        return true;
    }
}
<?php

namespace Audero\WebBundle\Services\Pusher\Pusher;

use Ratchet\ConnectionInterface;
use Ratchet\Wamp\Topic;
use Ratchet\Wamp\WampServerInterface;
use Symfony\Component\Security\Core\SecurityContext;

/**
 * Class PusherServer
 * @package Audero\WebBundle\Services\Pusher
 */
class PusherServer implements WampServerInterface, OutputInterface
{

    /**
     * @var ConnectionManager
     */
    protected $cm;

    /**
     * @param ConnectionManager $cm
     */
    public function __construct(ConnectionManager $cm)
    {
        $this->cm = $cm;
        if(!$cm->clearConnectionsFromDatabase()){
            $this->error("PusherServer", "Failed to remove all Connections"); die;
        }
    }

    /**
     * @param ConnectionInterface $conn
     */
    public function onOpen(ConnectionInterface $conn)
    {
        if (!$this->cm->hasPermissions($conn)) {
            $this->error("PusherServer", "Rejected connection (Permissions)");
            $conn->close();
            return;
        }

        try {
            $this->cm->addConnection($conn);
        } catch (\Exception $e) {
            $this->error("PusherServer", $e->getMessage());
            $conn->close();
            die;
        }

        $this->notification("PusherServer", "Added new connection");
    }

    /**
     * @param ConnectionInterface $conn
     */
    public function onClose(ConnectionInterface $conn)
    {
        try {
            $this->cm->removeConnection($conn);
        } catch (\Exception $e) {
            $this->error("PusherServer", $e->getMessage());
            die;
        }

        $this->notification("PusherServer", "Removed connection");
    }
    
    /**
     * @param ConnectionInterface $conn
     * @param Topic|string $topic
     */
    public function onSubscribe(ConnectionInterface $conn, $topic)
    {
        try {
            if(!($topic instanceof Topic)) {
                throw new \Exception('Received $topic is not an instance of Topic');
            }
            if (!$this->cm->hasPermissions($conn, $topic)) {
                $this->error("PusherServer", "Rejected subscription (Permissions)");
                $conn->close();
                return;
            }
            if ($this->cm->addSubscription($conn, $topic)) {
                $this->notification("PusherServer", "Added new subscription");
            } else {
                $this->error("PusherServer", "User tried to subscribe to not existing topic");
                $conn->close();
            }
        } catch (\Exception $e) {
            $this->error("PusherServer", $e->getMessage());
            $conn->close();
            die;
        }
    }

    /**
     * Should be never called
     *
     * @param ConnectionInterface $conn
     * @param Topic|string $topic
     */
    public function onUnSubscribe(ConnectionInterface $conn, $topic)
    {
        $conn->close();
    }

    /**
     * @param ConnectionInterface $conn
     * @param string $id
     * @param Topic|string $topic
     * @param array $params
     */
    public function onCall(ConnectionInterface $conn, $id, $topic, array $params)
    {
        $conn->callError($id, $topic, 'You are not allowed to make calls')->close();
    }

    /**
     * @param ConnectionInterface $conn
     * @param Topic|string $topic
     * @param string $event
     * @param array $exclude
     * @param array $eligible
     */
    public function onPublish(ConnectionInterface $conn, $topic, $event, array $exclude, array $eligible)
    {
        $conn->close();
    }

    /**
     * @param ConnectionInterface $conn
     * @param \Exception $e
     */
    public function onError(ConnectionInterface $conn, \Exception $e)
    {
    }

    /**
     * @param $entity
     * @param $text
     */
    public function error($entity, $text)
    {
        echo "{$entity} Error: " . $text . "\n";
    }

    /**
     * @param $entity
     * @param $text
     */
    public function notification($entity, $text)
    {
        echo "{$entity}: " . $text . "\n";
    }
}
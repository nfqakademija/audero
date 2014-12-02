<?php

namespace Audero\WebBundle\Services\Pusher;

use Audero\WebBundle\Entity\UserConnection;
use Ratchet\ConnectionInterface;
use Ratchet\Wamp\Topic;
use Ratchet\Wamp\WampServerInterface;
use Symfony\Component\Security\Core\SecurityContext;

class PusherServer implements WampServerInterface, OutputInterface {

    protected $cm;

    public function __construct(ConnectionManager $cm) {
        $this->cm = $cm;
    }

    public function onOpen(ConnectionInterface $conn) {
        if(!$this->cm->hasPermissions($conn)) {
            $this->error("PusherServer", "Rejected connection (Permissions)");
            $conn->close(); return;
        }

        try{
            $this->cm->addConnection($conn);
        }catch (\Exception $e) {
            $this->error("PusherServer", $e->getMessage());
            $conn->close(); die;
        }

        $this->notification("PusherServer", "Added new connection");
    }
    public function onClose(ConnectionInterface $conn) {
        try{
            $this->cm->removeConnection($conn);
        }catch(\Exception $e) {
            $this->error("PusherServer", $e->getMessage());
            $conn->close(); die;
        }

        $this->notification("PusherServer", "Removed connection");
    }
    public function onSubscribe(ConnectionInterface $conn, $topic) {
        if(!$this->cm->hasPermissions($conn, $topic)) {
            $this->error("PusherServer", "Rejected subscription");
            $conn->close(); return;
        }

        try{
            if($this->cm->addSubscription($conn, $topic)) {
                $this->notification("PusherServer", "Added new subscription");
            }else{
                $this->error("PusherServer", "User tried to subscribe to not existing topic");
            }
        }catch(\Exception $e) {
            $this->error("PusherServer", $e->getMessage());
            $conn->close(); die;
        }

    }
    public function onUnSubscribe(ConnectionInterface $conn, $topic) {
        $conn->close();
    }
    public function onCall(ConnectionInterface $conn, $id, $topic, array $params) {
        $conn->callError($id, $topic, 'You are not allowed to make calls')->close();
    }
    public function onPublish(ConnectionInterface $conn, $topic, $event, array $exclude, array $eligible) {
        $conn->close();
    }
    public function onError(ConnectionInterface $conn, \Exception $e) {
    }

    public function error($entity, $text)
    {
        echo "{$entity} Error: ".$text."\n";
    }

    public function notification($entity, $text)
    {
        echo "{$entity}: ".$text."\n";
    }
}
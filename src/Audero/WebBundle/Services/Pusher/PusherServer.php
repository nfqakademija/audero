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
            $this->error("Rejected connection (Permissions)");
            $conn->close(); return;
        }

        if($this->cm->addConnection($conn)) {
            $this->notification("Added new connection");
        }else{
            $this->error("Rejected connection");
            $conn->close();
        }
    }
    public function onClose(ConnectionInterface $conn) {
        $this->notification("Closing connection");
        $this->cm->removeConnection($conn);
    }
    public function onSubscribe(ConnectionInterface $conn, $topic) {
        if(!$this->cm->hasPermissions($conn, $topic)) {
            $this->error("Rejected subscription");
            $conn->close(); return;
        }

        if($this->cm->addSubscriber($conn, $topic)) {
            $this->notification("Added new subscription");
        }else{
            $this->error("Refused subscription");
        }
    }
    public function onUnSubscribe(ConnectionInterface $conn, $topic) {
        //$this->cm->removeSubscription($conn, $topic);
    }
    public function onCall(ConnectionInterface $conn, $id, $topic, array $params) {
        // In this application if clients send data it's because the user hacked around in console
        $conn->callError($id, $topic, 'You are not allowed to make calls')->close();
    }
    public function onPublish(ConnectionInterface $conn, $topic, $event, array $exclude, array $eligible) {
        // In this application if clients send data it's because the user hacked around in console
        $conn->close();
    }
    public function onError(ConnectionInterface $conn, \Exception $e) {
    }

    public function error($text)
    {
        echo "Error: ".$text."\n";
    }

    public function notification($text)
    {
        echo $text."\n";
    }
}
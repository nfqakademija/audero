<?php

namespace Audero\WebBundle\Services\Pusher;

use Audero\WebBundle\Entity\UserConnection;
use Ratchet\ConnectionInterface;
use Ratchet\Wamp\Topic;
use Ratchet\Wamp\WampServerInterface;
use Symfony\Component\Security\Core\SecurityContext;
use Ratchet\Wamp\ServerProtocol as WAMP;

class PusherServer implements WampServerInterface {

    protected $cm;

    public function __construct(ConnectionManager $cm) {
        $this->cm = $cm;
    }

    public function onSubscribe(ConnectionInterface $conn, $topic) {
        if(!$this->cm->isAvailable($topic)) {
            echo "User tried to subscribe to not existing topic (".$topic->getId().")\n"; return;
        }

        if(!$this->cm->hasPermissions($conn, $topic)) {
            echo "User tried to subscribe to topic (".$topic->getId() .") without having right permissions \n"; return;
        }

        $newConn = $this->cm->addSubscriber($conn, $topic);
        if($newConn) {
            echo "Added new subscription \n";
        }else{
            echo "Refused subscription \n";
        }
    }
    public function onUnSubscribe(ConnectionInterface $conn, $topic) {
        $this->cm->removeSubscription($conn, $topic);
    }
    public function onOpen(ConnectionInterface $conn) {
        if(!$this->cm->hasPermissions($conn)) {
            echo "Connection doesn't have right permissions \n";
            $conn->close();
        }

        if($this->cm->addConnection($conn)) {
            echo "Added new connection \n";
        }else{
            echo "Failed to add new connection \n";
            $conn->close();
        }
    }
    public function onClose(ConnectionInterface $conn) {
        echo "Closing connection \n";
        $this->cm->removeConnection($conn);
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
}
<?php

namespace Audero\WebBundle\Services\Pusher;

use Ratchet\ConnectionInterface;
use Ratchet\Wamp\WampServerInterface;
use Symfony\Component\Security\Core\SecurityContext;

class PusherServer implements WampServerInterface {

    protected $subscribedTopics = array();
    private $cm;

    public function __construct(ConnectionManager $cm) {
        $this->cm = $cm;
    }

    public function onSubscribe(ConnectionInterface $conn, $topic) {
        // checking if user has right permissions
        if(!$this->cm->hasPermissions($conn, $topic)) {
            $conn->close(); return;
        }

/*        $security_main = unserialize($conn->Session->get('_security_main'));
        if($security_main) {
            $user = $security_main->getUser();
            $this->cm->setUserConnId($user, $conn->resourceId);
        }*/

        // adding to subscribed topics
        if(!isset($this->subscribedTopics[$topic->getId()])) {
            $this->subscribedTopics[$topic->getId()] = $topic;
        }
/*
        $user = unserialize($conn->Session->all())->getUser();
        var_dump($user); die;*/
    }
    public function onUnSubscribe(ConnectionInterface $conn, $topic) {
    }
    public function onOpen(ConnectionInterface $conn) {
    }
    public function onClose(ConnectionInterface $conn) {
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
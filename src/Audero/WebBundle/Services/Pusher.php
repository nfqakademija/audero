<?php

namespace Audero\WebBundle\Services;

use Ratchet\ConnectionInterface;
use Ratchet\Wamp\WampServerInterface;
use Symfony\Component\Security\Core\SecurityContext;

class Pusher implements WampServerInterface {

    private $connManager;
    protected $subscribedTopics = array();

    public function __construct(SocketConnectionManager $connManager) {
        $this->connManager = $connManager;
    }
    public function onSubscribe(ConnectionInterface $conn, $topic) {
        // checking if user has right permissions
        if(!$this->connManager->hasPermissions($conn, $topic)) {
            return $conn->close();
        }

        // adding to subscribed topics
        if(!isset($this->subscribedTopics[$topic->getId()])) {
            $this->subscribedTopics[$topic->getId()] = $topic;
        }
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

    /**
     * @param string JSON'ified string we'll receive from ZeroMQ
     */
    public function onPush($entry) {
        $entryData = json_decode($entry, true);

        // If the lookup topic object isn't set there is no one to publish to
        if (!array_key_exists('channel', $entryData) || !array_key_exists('data', $entryData) ||
            !array_key_exists($entryData['channel'], $this->subscribedTopics)) {
            return;
        }

        $topic = $this->subscribedTopics[$entryData['channel']];

        // re-send the data to all the clients subscribed to that category
        $topic->broadcast($entryData['data']);
    }
}
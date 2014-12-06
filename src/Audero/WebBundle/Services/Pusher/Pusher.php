<?php

namespace Audero\WebBundle\Services\Pusher;

use React\Socket\ConnectionInterface;
use Ratchet\Wamp\ServerProtocol as WAMP;

/**
 * Class Pusher
 * @package Audero\WebBundle\Services\Pusher
 */
class Pusher extends PusherServer {

    /**
     * @param ConnectionManager $cm
     */
    public function __construct(ConnectionManager $cm) {
        parent::__construct($cm);
    }

    /**
     * @param $jsonPacket
     */
    public function execute($jsonPacket) {
        $packet = json_decode($jsonPacket);
        if(!$packet){
            $this->error("Pusher", "Received packet equals null"); return;
        }
        if(!isset($packet->command) || !isset($packet->topic) || !isset($packet->data)) {
            $this->error("Pusher", "Received packet is not properly formatted"); return;
        }

        switch ($packet->command) {
            case 'push' :
                $this->push($packet->topic, $packet->data);
                break;
            case 'send':
               // $this->send($packet->data);
                break;
            default:
                $this->error("Pusher", "Received packet contains unknown command");
        }
    }


    /**
     * @param $topicId
     * @param null $data
     */
    private function push($topicId, $data = null) {
        if(!($topic = $this->cm->getTopicById($topicId))) {
            $this->error("Pusher", "Topic ".$topicId." is unavailable"); return;
        }

        if(is_null($data)) {
            $this->error("Pusher", "Received data equals null"); return;
        }

        $topic->broadcast($data);
    }

    // TODO parameters to conn, data
    /**
     * @param ConnectionInterface $conn
     * @param $topic
     * @param $data
     */
    private function send(ConnectionInterface $conn, $topic, $data) {
        $conn->send(json_encode(array(WAMP::MSG_EVENT, (string) $topic, $data)));
    }
}
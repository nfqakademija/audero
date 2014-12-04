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
            $this->error("Pusher", "Pusher execute: Received packet equals null"); return;
        }
        if(!isset($packet->command) || !isset($packet->data)) {
            $this->error("Pusher", "Pusher execute: Received packet does not contain command or data"); return;
        }

        switch ($packet->command) {
            case 'push' :
                $this->push($packet->data);
                break;
            case 'send':
               // $this->send($packet->data);
                break;
        }
    }

    /**
     * @param $data
     */
    private function push($data) {
        if(!$data){
            $this->error("Pusher", "Pusher push: null data reveived"); return;
        }

        if(!isset($data->topic) || !isset($data->data)) {
            $this->error("Pusher", "Pusher push: Received data is not properly formatted"); return;
        }

        if(!($topic = $this->cm->getTopicById($data->topic))) {
            $this->error("Pusher", "Pusher push: Topic ".$data->topic." is unavailable"); return;
        }

        $topic->broadcast($data->data);
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
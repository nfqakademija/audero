<?php

namespace Audero\WebBundle\Services\Pusher;

use React\Socket\ConnectionInterface;
use Ratchet\Wamp\ServerProtocol as WAMP;

class Pusher extends PusherServer {

    public function __construct(ConnectionManager $cm) {
        parent::__construct($cm);
    }

    public function execute($jsonPacket) {
        $packet = json_decode($jsonPacket);
        if(!$packet){
            echo "Pusher execute: Received packet equals null \n"; return;
        }
        if(!isset($packet->command) || !isset($packet->data)) {
            echo "Pusher execute: Received packet does not contain command or data \n"; return;
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

    private function push($data) {
        if(!$data){
            echo "Pusher push: null data revieved \n"; return;
        }

        if(!isset($data->topic) || !isset($data->data)) {
            echo "Pusher push: Received data is not properly formatted \n"; return;
        }

        if(!($topic = $this->cm->getTopicById($data->topic))) {
            echo "Pusher push: Topic ".$data->topic." is unavailable \n"; return;
        }

        $topic->broadcast($data->data);
    }

    private function send(ConnectionInterface $conn, $topic, $data) {
        $conn->send(json_encode(array(WAMP::MSG_EVENT, (string) $topic, $data)));
    }
}
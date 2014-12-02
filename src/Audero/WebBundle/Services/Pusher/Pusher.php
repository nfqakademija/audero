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
    private function send(ConnectionInterface $conn, $topic, $data) {
        $conn->send(json_encode(array(WAMP::MSG_EVENT, (string) $topic, $data)));
    }
}
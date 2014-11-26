<?php

namespace Audero\WebBundle\Services\Pusher;

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
                $this->send($packet->data);
                break;
        }
    }

    private function push($data) {
        if(!$data){
            echo "Pusher push: null data revieved \n"; return;
        }

        if(!isset($data->channel) || !isset($data->data)) {
            echo "Pusher push: Received data is not properly formatted \n"; return;
        }
        
        if(array_key_exists($data->channel, $this->subscribedTopics)) {
            $topic = $this->subscribedTopics[$data->channel];
            $topic->broadcast($data->data);
        }

    }

    private function send($parameters) {

    }
}
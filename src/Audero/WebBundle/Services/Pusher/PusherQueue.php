<?php

namespace Audero\WebBundle\Services\Pusher;

class PusherQueue {

    public function add($data) {
        $context = new \ZMQContext();
        $socket = $context->getSocket(\ZMQ::SOCKET_PUSH, 'pusher');
        $socket->connect("tcp://127.0.0.1:5555");
        $socket->send(json_encode($data));
    }

} 
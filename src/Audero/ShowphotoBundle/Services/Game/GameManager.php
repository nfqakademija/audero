<?php

namespace Audero\ShowphotoBundle\Services\Game;

use Audero\ShowphotoBundle\Services\Game\RequestProvider;
use Doctrine\ORM\EntityManager;

class Manager {

    private $em;
    private $requestProvider;

    public $skaicius = 0;

    public function __construct(EntityManager $em, RequestProvider $requestProvider) {
        $this->em = $em;
        $this->requestProvider = $requestProvider;
    }

    public function start() {
        $i = 0;
        while(true) {

            $request = $this->requestProvider->generate();

            $data = array(
                'channel' => "game_request",
                'data'    => "Request ".$i." ".$request->getTitle()
            );

            $context = new \ZMQContext();
            $socket = $context->getSocket(\ZMQ::SOCKET_PUSH, 'pusher');
            $socket->connect("tcp://127.0.0.1:5555");
            $socket->send(json_encode($data));
            sleep(30);
        }
    }

}
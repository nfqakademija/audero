<?php

namespace Audero\ShowphotoBundle\Services\Game;

use Audero\ShowphotoBundle\Entity\Player;
use Audero\ShowphotoBundle\Services\Game\PlayerManager;
use Audero\ShowphotoBundle\Services\Game\PhotoRequest;
use Doctrine\ORM\EntityManager;

class Manager {

    private $em;
    private $photoRequest;
    private $playerManager;

    public function __construct(EntityManager $em, PhotoRequest $photoRequest, PlayerManager $playerManager) {
        $this->em = $em;
        $this->photoRequest = $photoRequest;
        $this->playerManager = $playerManager;
    }

    public function start() {
        $i = 0;

        while(true) {
            $i++;
            $request = $this->photoRequest->generate();
            if($request) {
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

}
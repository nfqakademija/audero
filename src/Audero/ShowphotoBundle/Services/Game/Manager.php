<?php

namespace Audero\ShowphotoBundle\Services\Game;

use Audero\ShowphotoBundle\Entity\Player;
use Audero\ShowphotoBundle\Services\Game\PlayerManager;
use Audero\ShowphotoBundle\Services\Game\PhotoRequest;
use Audero\WebBundle\Services\Pusher\Commands;
use Audero\WebBundle\Services\Pusher\Packet;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Validator\Constraints\DateTime;

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
        while(true) {
            // getting admin options
            $options = $this->em->getRepository("AuderoBackendBundle:OptionsRecord")->findCurrent();
            if(!$options) {
                echo "GameManager: admin options not found \n";
                sleep(10); continue;
            }

            // Generating new response from player's wish
            $data = $this->photoRequest->generate();
            if(!isset($data['request']) || !isset($data['wish'])) {
                echo "Could not get new request \n";
                sleep(10); continue;
            }
            $request = $data['request'];
            $wish = $data['wish'];

            // removing wish from which request was generated from, persisting new request
            try{
                $this->em->remove($wish);
                $this->em->persist($request);
                $this->em->flush();
            }catch (\Exception $e) {
                echo $e->getMessage(); die;
            }

            // setting time until valid
            $validUntil = $request->getDate()->add(new \DateInterval('PT'.$options->getTimeForResponse().'S'));

            $data = array(
                'command' => 'push',
                'data' => array(
                    'topic' => "game_request",
                    'data'    => array(
                        'request' => $request->getTitle(),
                        'validUntil' => $validUntil,
                    )
                )
            );

            // Broadcasting
            $context = new \ZMQContext();
            $socket = $context->getSocket(\ZMQ::SOCKET_PUSH, 'pusher');
            $socket->connect("tcp://127.0.0.1:5555");
            $socket->send(json_encode($data));
            sleep(30);
        }
    }
}
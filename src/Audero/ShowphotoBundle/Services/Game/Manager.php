<?php

namespace Audero\ShowphotoBundle\Services\Game;

use Audero\ShowphotoBundle\Services\OutputInterface;
use Doctrine\ORM\EntityManager;

class Manager implements OutputInterface {

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
                $this->error("Admin options not found");
                sleep(10); continue;
            }

            // Generating new request
            $data = $this->photoRequest->generate();
            if(!isset($data['request']) || !isset($data['wish'])) {
                $this->error("Could not get new request");
                sleep(10); continue;
            }

            $request = $data['request'];
            $wish = $data['wish'];

            // wish -> request
/*            try{
                $this->em->remove($wish);
                $this->em->persist($request);
                $this->em->flush();
            }catch (\Exception $e) {
                echo $e->getMessage(); die;
            }*/

            $data = array(
                'command' => 'push',
                'data' => array(
                    'topic' => "game_request",
                    'data'    => array(
                        'request' => $request->getTitle(),
                        'user'    => $request->getUser()->getUsername(),
                        'validUntil' => $request->getDate()->add(new \DateInterval('PT'.$options->getTimeForResponse().'S')),
                    )
                )
            );
            // Broadcasting
            $context = new \ZMQContext();
            $socket = $context->getSocket(\ZMQ::SOCKET_PUSH, 'pusher');
            $socket->connect("tcp://127.0.0.1:5555");
            $socket->send(json_encode($data));
            //

            sleep($options->getTimeForResponse());
        }
    }

    public function error($text)
    {
        echo "Game Manager error: ".$text."\n";
    }

    public function notification($text)
    {
        echo "Game Manager: ".$text."\n";
    }
}
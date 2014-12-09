<?php

namespace Audero\ShowphotoBundle\Services\Game;

use Audero\ShowphotoBundle\Entity\ChatMessage;
use Audero\WebBundle\Services\Pusher\PusherQueue;

class Chat {

    private $pusherQueue;

    public function __construct(PusherQueue $pusherQueue) {
        $this->pusherQueue = $pusherQueue;
    }

    public function broadcast(ChatMessage $message)
    {
        if(!$message->getUser()) {
            throw new \Exception('Could not retrieve user from message entity');
        }
        $data = array(
            'topic' => 'chat',
            'data' => array(
                'author' => $message->getUser()->getUsername(),
                'text' => $message->getText(),
            )
        );

        $this->pusherQueue->add($data);
    }

} 
<?php

namespace Audero\WebBundle\Services\Pusher;

use Audero\ShowphotoBundle\Entity\User;

/**
 * Class Connection
 * @package Audero\WebBundle\Services\Pusher
 */
class Connection
{
    /**
     * @var PusherQueue
     */
    private $pusherQueue;

    /**
     * @param PusherQueue $pusherQueue
     */
    public function __construct(PusherQueue $pusherQueue)
    {
        $this->pusherQueue = $pusherQueue;
    }

    /**
     * @param User $user
     */
    public function closeByUser(User $user)
    {
        $data = array(
            'command' => 'closeByUsername',
            'parameters' => array('username' => $user->getUsername())
        );

        $this->pusherQueue->add($data);
    }

    /**
     * @param $ip
     */
    public function closeByIp($ip)
    {
        $data = array(
            'command' => 'closeByIp',
            'parameters' => array('ip' => $ip)
        );

        $this->pusherQueue->add($data);
    }
}
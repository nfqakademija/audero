<?php

namespace Audero\WebBundle\Services\Pusher\Pusher;

use Ratchet\ConnectionInterface;
use Ratchet\Wamp\ServerProtocol as WAMP;

/**
 * Class Pusher
 * @package Audero\WebBundle\Services\Pusher
 */
class Pusher extends PusherServer
{

    /**
     * @param ConnectionManager $cm
     */
    public function __construct(ConnectionManager $cm)
    {
        parent::__construct($cm);
    }

    /**
     * @param $jsonPacket
     */
    public function execute($jsonPacket)
    {
        $packet = json_decode($jsonPacket);
        if (!$packet) {
            $this->error("Pusher", "Failed to decode received packet");
            return;
        }

        if (isset($packet->command) && isset($packet->parameters)) {
            $this->executeCommand($packet->command, $packet->parameters);
            return;
        }

        if (isset($packet->topic) && isset($packet->data)) {
            /*Sending only to specific user*/
            if (isset($packet->username)) {
                $conn = $this->cm->getConnectionByUsername($packet->username);
                if ($conn) {
                    $this->send($conn, $packet->topic, $packet->data);
                }
            } else {
                $this->broadcast($packet->topic, $packet->data);
            }

            return;
        }

        $this->error("Pusher", "Received packet is not properly formatted");
    }

    /**
     * @param $command
     * @param $parameters
     */
    private function executeCommand($command, $parameters)
    {
        switch ($command) {
            case 'closeByUsername':
                try {
                    $this->cm->closeByUsername($parameters->username);
                } catch (\Exception $e) {
                    $this->error("Pusher", $e->getMessage());
                }
                break;
            case 'closeByIp':
                try {
                    $this->cm->closeByIp($parameters->ip);
                } catch (\Exception $e) {
                    $this->error("Pusher", $e->getMessage());
                }
                break;
            default:
                $this->error("Pusher", "Wrong command {$command}");
        }
    }

    /**
     * @param ConnectionInterface $conn
     * @param $topic
     * @param $data
     */
    private function send(ConnectionInterface $conn, $topic, $data)
    {
        $conn->send(json_encode(array(WAMP::MSG_EVENT, (string)$topic, $data)));
    }

    /**
     * @param $topicId
     * @param $data
     */
    private function broadcast($topicId, $data)
    {
        if (!($topic = $this->cm->getTopicById($topicId))) {
            $this->error("Pusher", "Topic " . $topicId . " is unavailable");
            return;
        }

        if (is_null($data)) {
            $this->error("Pusher", "Received data equals null");
            return;
        }

        $topic->broadcast($data);
    }
}
<?php

namespace Audero\BackendBundle\Entity;

class Options
{
    private $timeForResponse;

    private $playersInOneRoom;

    public function setTimeForResponse($timeForResponse)
    {
        $this->timeForResponse = $timeForResponse;
    }

    public function getTimeForResponse()
    {
        return $this->timeForResponse;
    }

    public function setPlayersInOneRoom($playersInOneRoom)
    {
        $this->playersInOneRoom = $playersInOneRoom;
    }

    public function getPlayersInOneRoom()
    {
        return $this->playersInOneRoom;
    }
}


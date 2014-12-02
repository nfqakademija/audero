<?php

namespace Audero\BackendBundle\Entity;

/**
 * Class Options
 * @package Audero\BackendBundle\Entity
 */
class Options
{
    /**
     * @var
     */
    private $timeForResponse;

    /**
     * @var
     */
    private $playersInOneRoom;

    /**
     * @var
     */
    private $playerWishesCount;

    /**
     * @param $timeForResponse
     */
    public function setTimeForResponse($timeForResponse)
    {
        $this->timeForResponse = $timeForResponse;
    }

    /**
     * @return mixed
     */
    public function getTimeForResponse()
    {
        return $this->timeForResponse;
    }

    /**
     * @param $playersInOneRoom
     */
    public function setPlayersInOneRoom($playersInOneRoom)
    {
        $this->playersInOneRoom = $playersInOneRoom;
    }

    /**
     * @return mixed
     */
    public function getPlayersInOneRoom()
    {
        return $this->playersInOneRoom;
    }

    /**
     * @return mixed
     */
    public function getPlayerWishesCount()
    {
        return $this->playerWishesCount;
    }

    /**
     * @param mixed $playerWishesCount
     */
    public function setPlayerWishesCount($playerWishesCount)
    {
        $this->playerWishesCount = $playerWishesCount;
    }
}


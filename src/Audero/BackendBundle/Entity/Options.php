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
     * @var integer
     */
    private $maxPlayers;

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
     * @param $maxPlayers
     */
    public function setMaxPlayers($maxPlayers)
    {
        $this->maxPlayers = $maxPlayers;
    }

    /**
     * @return integer
     */
    public function getMaxPlayers()
    {
        return $this->maxPlayers;
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


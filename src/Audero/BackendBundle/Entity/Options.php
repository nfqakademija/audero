<?php

namespace Audero\BackendBundle\Entity;

/**
 * Class Options
 * @package Audero\BackendBundle\Entity
 */
class Options
{
    /**
     * @var integer
     */
    private $timeForRequest;

    /**
     * @var integer
     */
    private $minPlayers;

    /**
     * @var integer
     */
    private $maxPlayers;

    /**
     * @var integer
     */
    private $timeForWinnerQueue;

    /**
     * @var integer
     */
    private $playerWishesCount;


    /**
     * @param $timeForRequest
     */
    public function setTimeForRequest($timeForRequest)
    {
        $this->timeForRequest = $timeForRequest;
    }

    /**
     * @return integer
     */
    public function getTimeForRequest()
    {
        return $this->timeForRequest;
    }

    /**
     * @param $minPlayers
     */
    public function setMinPlayers($minPlayers)
    {
        $this->minPlayers = $minPlayers;
    }

    /**
     * @return integer
     */
    public function getMinPlayers()
    {
        return $this->minPlayers;
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
     * @return integer
     */
    public function getTimeForWinnerQueue()
    {
        return $this->timeForWinnerQueue;
    }

    /**
     * @param integer $timeForWinnerQueue
     */
    public function setTimeForWinnerQueue($timeForWinnerQueue)
    {
        $this->timeForWinnerQueue = $timeForWinnerQueue;
    }

    /**
     * @return integer
     */
    public function getPlayerWishesCount()
    {
        return $this->playerWishesCount;
    }

    /**
     * @param integer $playerWishesCount
     */
    public function setPlayerWishesCount($playerWishesCount)
    {
        $this->playerWishesCount = $playerWishesCount;
    }
}
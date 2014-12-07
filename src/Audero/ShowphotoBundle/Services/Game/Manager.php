<?php

namespace Audero\ShowphotoBundle\Services\Game;

use Audero\BackendBundle\Entity\Options;
use Audero\ShowphotoBundle\Entity\PhotoRequest as PRequestEntity;
use Audero\ShowphotoBundle\Services\OutputInterface;
use Doctrine\ORM\EntityManager;

/**
 * Class Manager
 * @package Audero\ShowphotoBundle\Services\Game
 */
class Manager implements OutputInterface
{
    /**
     * @var EntityManager
     */
    private $em;

    /**
     * @var PhotoRequest
     */
    private $photoRequest;

    /**
     * @var Player
     */
    private $player;

    /**
     * @var WinnerQueue
     */
    private $winnerQueue;

    /**
     * @var Wish
     */
    private $wish;

    /**
     * @param EntityManager $em
     * @param PhotoRequest $photoRequest
     * @param Player $player
     * @param WinnerQueue $winnerQueue
     * @param Wish $wish
     */
    public function __construct(EntityManager $em, PhotoRequest $photoRequest,
                                Player $player, WinnerQueue $winnerQueue, Wish $wish)
    {
        $this->em = $em;
        $this->photoRequest = $photoRequest;
        $this->player = $player;
        $this->winnerQueue = $winnerQueue;
        $this->wish = $wish;

        if(!$player->clearPlayersFromDatabase()){
            $this->error("Failed to remove all Players"); die;
        }
    }

    /**
     * @throws \Exception
     */
    public function start()
    {
        while (true) {
            /*Getting admin options*/
            $options = $this->em->getRepository("AuderoBackendBundle:OptionsRecord")->findCurrent();
            /**@var Options $options*/
            if (!$options) {
                $this->error("Admin options not found");
                sleep(5);
                continue;
            }

            /*Checking if there's enough players */
            $playersCount = (int) $this->em->getRepository("AuderoShowphotoBundle:Player")->getPlayersCount();
            if($playersCount < $options->getMinPlayers()) {
                $this->error("There's not enough players. Waiting...");
                sleep(10);
                continue;
            }

            $requestEntity = $this->prepareNewRequest();
            if(!($requestEntity instanceof PRequestEntity)) {
                $this->error("Failed to get newly generated request");
                sleep(10); continue;
            }

            $this->wish->broadcast($requestEntity->getUser());
            $this->photoRequest->broadcast($requestEntity);

            /*waiting until request time finishes*/
            $now = new \DateTime('now');
            $sleepTime = $this->photoRequest->getValidUntil($requestEntity) - $now->getTimestamp();
            sleep($sleepTime);

            /*broadcasting winners queue*/
            $now = new \DateTime('now');
            $showUntil = $now->add(new \DateInterval('PT'.$options->getTimeForWinnerQueue().'S'))->getTimestamp();
            $this->winnerQueue->broadcast($requestEntity, $showUntil);
            sleep($options->getTimeForWinnerQueue());
        }
    }

    /**
     * @return mixed
     * @throws \Doctrine\DBAL\ConnectionException
     */
    private function prepareNewRequest() {
        /*Generating new Request*/
        $data = $this->photoRequest->generate();
        if (!isset($data['request']) || !isset($data['wish'])) {
            return null;
        }

        $requestEntity = $data['request'];
        $wishEntity = $data['wish'];

        /*Creating new request entity*/
        $this->em->getConnection()->beginTransaction();
        try{
            $this->em->remove($wishEntity);
            $this->em->persist($requestEntity);
            $this->em->flush();
            $this->em->getConnection()->commit();
        }catch (\Exception $e) {
            $this->em->getConnection()->rollback();
            $this->error($e->getMessage()); die;
        }

        return $requestEntity;
    }

    /**
     * @param $text
     */
    public function error($text)
    {
        echo "Game Manager error: " . $text . "\n";
    }

    /**
     * @param $text
     */
    public function notification($text)
    {
        echo "Game Manager: " . $text . "\n";
    }
}
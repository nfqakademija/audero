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
            if (!($options instanceof Options)) {
                $this->error("Admin options not found"); die;
            }

            /*Checking if there's enough players */
            $playersCount = (int) $this->em->getRepository("AuderoShowphotoBundle:Player")->getPlayersCount();
            if($playersCount < $options->getMinPlayers()) {
                $this->error("There's not enough players. Waiting...({$playersCount} < {$options->getMinPlayers()})");
                sleep(5); continue;
            }

            $this->notification("Generating new photo request");
            $requestEntity = $this->prepareNewRequest();
            if(!($requestEntity instanceof PRequestEntity)) {
                $this->error("Could not generate new request");
                sleep(5); continue;
            }

            /*Setting valid until before broadcasting*/
            try{
                $date = new \DateTime('now');
                $requestEntity->setValidUntil($date->add(new \DateInterval('PT'.$options->getTimeForRequest().'S')));
                $this->em->persist($requestEntity);
                $this->em->flush();
            }catch (\Exception $e) {
                $this->error($e->getMessage()); die;
            }

            $this->broadcastRequest($requestEntity);

            $this->waitUntilRequestExpires($requestEntity);

            /*$this->broadcastWinnerQueue($options, $requestEntity);*/

            /*Waiting*/
            /*$this->notification("Showing winners queue for {$options->getTimeForWinnerQueue()} sec");
            sleep($options->getTimeForWinnerQueue());*/

            //TODO kick all players who did not respond

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
     * @param PRequestEntity $photoRequest
     */
    private function broadcastRequest(PRequestEntity $photoRequest) {
        $user = $photoRequest->getUser();
        if(!$user) {
            $this->error("Failed to get user from photoRequest entity"); die;
        }
        try{
            $this->notification("Updating user ({$user}) wish list");
            $this->wish->broadcast($user);
            $this->notification("Broadcasting new request '{$photoRequest->getTitle()}'");
            $this->photoRequest->broadcast($photoRequest);
        }catch (\Exception $e) {
            $this->error($e->getMessage()); die;
        }
    }

    /**
     * @param Options $options
     * @param PRequestEntity $photoRequest
     */
    private function broadcastWinnerQueue(Options $options, PRequestEntity $photoRequest) {
        /*Broadcasting*/
        $this->notification("Broadcasting winners queue");
        try{
            $this->winnerQueue->broadcast($photoRequest, $options->getTimeForWinnerQueue());
        }catch (\Exception $e) {
            $this->error($e->getMessage()); die;
        }
    }

    /**
     * @param PRequestEntity $photoRequest
     */
    private function waitUntilRequestExpires(PRequestEntity $photoRequest) {
        /*waiting until request time finishes*/
        $this->notification("Waiting until request time expires (".
            date('h:i:s ', $photoRequest->getValidUntil()->getTimestamp()) .")");
        $now = new \DateTime('now');
        $sleepTime = $photoRequest->getValidUntil()->getTimestamp() - $now->getTimestamp();
        if($sleepTime > 0) {
            sleep($sleepTime);
        }
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
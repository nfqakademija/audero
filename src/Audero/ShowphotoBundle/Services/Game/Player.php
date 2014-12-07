<?php

namespace Audero\ShowphotoBundle\Services\Game;

use Audero\BackendBundle\Entity\Options;
use Audero\ShowphotoBundle\Entity\Player as PlayerEntity;
use Audero\ShowphotoBundle\Entity\User;
use Audero\WebBundle\Entity\UserConnection;
use Audero\WebBundle\Entity\UserSubscription;
use Audero\WebBundle\Services\Pusher\PusherQueue;
use Doctrine\ORM\EntityManager;
use Symfony\Component\CssSelector\Exception\InternalErrorException;

/**
 * Class Player
 * @package Audero\ShowphotoBundle\Services\Game
 */
class Player
{
    /**
     * @var EntityManager
     */
    private $em;

    /**
     * @var PusherQueue
     */
    private $pusherQueue;

    /**
     * @param EntityManager $em
     * @param PusherQueue $pusherQueue
     */
    public function __construct(EntityManager $em, PusherQueue $pusherQueue)
    {
        $this->em = $em;
        $this->pusherQueue = $pusherQueue;
    }

    /**
     * @param User $user
     * @return PlayerEntity|null|object
     * @throws InternalErrorException
     */
    public function add(User $user)
    {
        $playersRepo = $this->em->getRepository("AuderoShowphotoBundle:Player");

        /*checking if player is already checked in */
        if ($player = $playersRepo->findOneBy(array('user' => $user->getId()))) {
            return $player;
        }

        /**@var Options $options */
        $options = $this->em->getRepository("AuderoBackendBundle:OptionsRecord")->findCurrent();
        if (!$options) {
            throw new InternalErrorException();
        }

        if ($playersRepo->getPlayersCount() < $options->getMaxPlayers()) {
            $player = new PlayerEntity();
            $player->setUser($user);

            $this->em->persist($player);
            $this->em->flush();

            return $player;
        }


        return null;
    }

    /**
     * @param PlayerEntity $player
     */
    public function remove(PlayerEntity $player)
    {
        $player->getUser()->setPlayer(null);
        $this->em->remove($player);
        $this->em->flush();
    }

    /**
     * @return integer
     */
    public function getPlayersCount()
    {
        return $this->em->getRepository("AuderoShowphotoBundle:Player")->getPlayersCount();
    }

    /**
     * @param User $user
     * @return null|object
     */
    public function isPlayer(User $user)
    {
        return $this->em->getRepository("AuderoShowphotoBundle:Player")->findOneBy(array("user" => $user));
    }

    /**
     *  Broadcasts all playing users
     */
    public function broadcast()
    {
        $players = $this->em->getRepository("AuderoShowphotoBundle:Player")->findAllOrderedByRate();

        $playersList = array();
        /**@var PlayerEntity $player */
        foreach ($players as $player) {
            $playersList[] = $player->getUser()->getUsername();
        }

        $data = array(
            'topic' => 'game',
            'data' => array(
                'type' => 'player',
                'players' => $playersList,
            )
        );

        $this->pusherQueue->add($data);
    }

    public function hasDisconnected(UserConnection $oldConnection) {
        $playerRepo = $this->em->getRepository("AuderoShowphotoBundle:Player");
        $player = $playerRepo->findOneBy(array('user'=>$oldConnection->getUser()));
        if(!$player) {
            return;
        }

        $userRepo = $this->em->getRepository("AuderoWebBundle:UserConnection");
        $connections = $userRepo->findBy(array('user'=>$oldConnection->getUser()));

        // TODO WRITE QUERY
        $removeFromPlayers = true;
        /**@var UserConnection $conn */
        foreach($connections as $conn) {
            /**@var UserSubscription $subscription*/
            foreach($conn->getSubscriptions() as $subscription) {
                if($subscription->getTopic() == "game") {
                    $removeFromPlayers = false;
                }
            }
        }

        if($removeFromPlayers) {
            $this->remove($player);
        }

    }

} 
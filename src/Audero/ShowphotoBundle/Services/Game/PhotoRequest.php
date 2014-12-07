<?php

namespace Audero\ShowphotoBundle\Services\Game;

use Audero\BackendBundle\Entity\Options;
use Audero\ShowphotoBundle\Entity\PhotoRequest as PRequestEntity;
use Audero\ShowphotoBundle\Entity\Player as PlayerEntity;
use Audero\ShowphotoBundle\Entity\Wish;
use Audero\WebBundle\Services\Pusher\PusherQueue;
use Cocur\Slugify\Slugify;
use Doctrine\ORM\EntityManager;

/**
 * Class PhotoRequest
 * @package Audero\ShowphotoBundle\Services\Game
 */
class PhotoRequest {

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
    public function __construct(EntityManager $em, PusherQueue $pusherQueue) {
        $this->em = $em;
        $this->pusherQueue = $pusherQueue;
    }

    /**
     * Generates a new Request
     *
     * @return array
     */
    public function generate() {
        /*Finding newest photo request*/
        $pRequestEntity = $this->em->getRepository('AuderoShowphotoBundle:PhotoRequest')->findNewest();
        if(!$pRequestEntity) {
            return $this->generatePlayersRequest();
        }
        /*finding it's best responses*/
        $pResponses = $this->em->getRepository('AuderoShowphotoBundle:PhotoResponse')->findBestResponses($pRequestEntity);
        if ($pResponses) {
            foreach($pResponses as $pResponse) {
                $wish = $this->em->getRepository('AuderoShowphotoBundle:Wish')->findUserFirstWish($pResponse['response']->getUser());
                if($wish) {
                    return $this->createRequest($wish);
                }
            }
        }

        return $this->generatePlayersRequest();
    }

    /**
     * Generates request from players wishes
     *
     * @return array
     */
    private function generatePlayersRequest() {
        $players = (array) $this->em->getRepository("AuderoShowphotoBundle:Player")->findAllOrderedByRate();
        /**@var PlayerEntity $player*/
        foreach($players as $player)  {
            $wish = $this->em->getRepository("AuderoShowphotoBundle:Wish")->findUserFirstWish($player->getUser());
            if($wish) {
                return $this->createRequest($wish);
            }
        }

        return null;
    }

    /**
     * @param Wish $wish
     * @return array
     */
    private function createRequest(Wish $wish) {
        $pResponseEntity = new PRequestEntity();
        $pResponseEntity->setTitle($wish->getTitle())
            ->setUser($wish->getUser())
            ->setSlug($this->createSlug($wish));

        return array('request' => $pResponseEntity, 'wish' => $wish);
    }

    /**
     * Creates photo request slug from wish title
     *
     * @param Wish $wish
     * @return string
     */
    public function createSlug(Wish $wish) {
        $slugify = new Slugify();
        return $slugify->slugify($wish->getTitle().' '.$wish->getUser()->getUsername());
    }


    /**
     * @param pRequestEntity $pRequestEntity
     */
    public function broadcast(pRequestEntity $pRequestEntity)
    {
        $data = array(
            'topic' => 'game',
            'data' => array(
                'type' => 'request',
                'requestTitle' => $pRequestEntity->getTitle(),
                'username' =>$pRequestEntity->getUser()->getUsername(),
                'validUntil' => $this->getValidUntil($pRequestEntity)
            )
        );

        $this->pusherQueue->add($data);
    }

    /**
     * Returns timestamp (valid Until) for photo request's Entity
     *
     * @param $pRequestEntity $request
     * @return int|null
     */
    public function getValidUntil(pRequestEntity $pRequestEntity)
    {
        /** @var Options $options */
        $options = $this->em->getRepository("AuderoBackendBundle:OptionsRecord")->findCurrent();
        if (!$options) {
            return null;
        }

        return $pRequestEntity->getDate()->add(new \DateInterval('PT' . $options->getTimeForRequest() . 'S'))->getTimestamp();
    }
} 
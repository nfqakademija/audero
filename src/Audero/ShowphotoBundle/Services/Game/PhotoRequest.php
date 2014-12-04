<?php

namespace Audero\ShowphotoBundle\Services\Game;

use Audero\ShowphotoBundle\Entity\PhotoRequest as Request;
use Audero\ShowphotoBundle\Entity\Wish;
use Cocur\Slugify\Slugify;
use Doctrine\ORM\EntityManager;

class PhotoRequest {

    private $em;

    public function __construct(EntityManager $em) {
        $this->em = $em;
    }

    /**
     * Generates a new Request
     *
     * @return array
     */
    public function generate() {
        // finding newest request
        $request = $this->em->getRepository('AuderoShowphotoBundle:PhotoRequest')->findNewest();
        if(!$request) {
            return $this->generatePlayersRequest();
        }

        // finding it's best responses
        $responses = $this->em->getRepository('AuderoShowphotoBundle:PhotoResponse')->findBest($request);
        if (!$responses) {
            return $this->generatePlayersRequest();
        }
        foreach($responses as $response) {
            $wish = $this->em->getRepository('AuderoShowphotoBundle:Wish')->findUserFirstWish($response['response']->getUser());
            if($wish) {
                return $this->createRequest($wish);
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
        $players = (array) $this->em->getRepository("AuderoShowphotoBundle:Player")->findOrderedByRank();
        foreach($players as $player)  {

        }
    }

    /**
     * @param Wish $wish
     * @return array
     */
    private function createRequest(Wish $wish) {
        $request = new Request();
        $request->setTitle($wish->getTitle())
            ->setUser($wish->getUser())
            ->setSlug($this->createSlug($wish));

        return array('request' => $request, 'wish' => $wish);
    }

    public function createSlug(Wish $wish) {
        $slugify = new Slugify();
        return $slugify->slugify($wish->getTitle().' '.$wish->getUser()->getUsername());
    }
} 
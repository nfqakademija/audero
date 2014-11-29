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

    /* *
     * Generates a new request
     * */
    public function generate() {
        // finding newest request
        $request = $this->em->getRepository('AuderoShowphotoBundle:PhotoRequest')->findNewestRequest();
        if(!$request) {
            return $this->generatePlayersRequest();
        }

        // finding it's best responses
        $responses = $this->em->getRepository('AuderoShowphotoBundle:PhotoResponse')->findBestResponses($request);
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

    private function generatePlayersRequest() {
        return null;
    }

    private function createRequest(Wish $wish) {
        $slugify = new Slugify();

        $request = new Request();
        $request->setTitle($wish->getTitle())
            ->setUser($wish->getUser())
            ->setSlug($slugify->slugify($wish->getTitle()));

        return $request;
    }
} 
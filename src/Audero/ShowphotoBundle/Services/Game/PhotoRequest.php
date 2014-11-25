<?php

namespace Audero\ShowphotoBundle\Services\Game;

use Audero\ShowphotoBundle\Entity\PhotoRequest as Request;
use Cocur\Slugify\Slugify;
use Doctrine\ORM\EntityManager;

class PhotoRequest {

    private $em;

    public function __construct(EntityManager $em) {
        $this->em = $em;
    }

    public function generate() {
        // finding last request
        $request = $this->em->getRepository('AuderoShowphotoBundle:PhotoRequest')->findLast();
        if($request) {
            // finding it's responses
            $responses = $this->em->getRepository('AuderoShowphotoBundle:PhotoResponse')->findOrderedByLikes($request->getId());
            if(count($responses) == 0) {
                return null;
            }
            // getting best response author's wish
            $wish = $this->em->getRepository('AuderoShowphotoBundle:Wish')->findUserFirstWish($responses[0]['response']->getUser());
            if($wish) {
                // removing user's wish
                $this->em->remove($wish);

                // creating new request
                $newRequest = new Request();
                $slugify = new Slugify();
                $newRequest->setTitle($wish->getTitle())
                           ->setUser($wish->getUser())
                           ->setSlug($slugify->slugify($wish->getTitle()));
                $this->em->persist($newRequest);

                $this->em->flush();
                return $newRequest;
            }

        }

        return null;
    }

    public function store() {

    }

} 
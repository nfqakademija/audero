<?php

namespace Audero\ShowphotoBundle\Services\Game;

use Audero\ShowphotoBundle\Form\PhotoResponseType;
use Symfony\Component\HttpFoundation\Request;
use Audero\ShowphotoBundle\Entity\PhotoRequest;
use Audero\ShowphotoBundle\Entity\PhotoResponse as Response;
use Audero\ShowphotoBundle\Entity\Wish;
use Cocur\Slugify\Slugify;
use Doctrine\ORM\EntityManager;

class PhotoResponse {

    private $em;

    public function __construct(EntityManager $em) {
        $this->em = $em;
    }

    public function handlePhotoResponse(Request $request) {
        $entity = new Response();
        $form = $this->createForm(new PhotoResponseType(), $entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
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
        var_dump("TO DO FROM PLAYERS"); die;
        return $this->createRequest(new Wish());
    }

    /**
     * @param Wish $wish
     * @return array
     */
    private function createRequest(Wish $wish) {
        // TODO MOVE TO SERVICE
        $slugify = new Slugify();
        //
        $request = new Request();
        $request->setTitle($wish->getTitle())
            ->setUser($wish->getUser())
            ->setSlug($slugify->slugify($wish->getTitle()));

        return array('request' => $request, 'wish' => $wish);
    }
} 
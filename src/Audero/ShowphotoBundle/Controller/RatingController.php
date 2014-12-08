<?php

namespace Audero\ShowphotoBundle\Controller;

use Audero\ShowphotoBundle\Entity\PhotoResponse;
use Audero\ShowphotoBundle\Entity\User;
use Audero\ShowphotoBundle\RatingEvents;
use Symfony\Component\CssSelector\Exception\InternalErrorException;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Audero\ShowphotoBundle\Entity\Rating;
use Audero\ShowphotoBundle\Event\FilterRatingEvent;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * Rating controller.
 *
 * @Route("/rating")
 */
class RatingController extends Controller
{
    /**
     * Creates a new Rating entity.
     *
     * @Route("/create", name="showphoto_rating_create")
     * @Method("POST")
     */
    public function createAction(Request $request)
    {
        $requestSlug = $request->get('request_slug', '');
        $responseAuthor = $request->get('response_author', '');
        /*like or dislike*/
        $rate = $request->get('rate') == 'true' ? true : false;
        $em = $this->getDoctrine()->getManager();

        if (!($user = $this->getUser())) {
            return new JsonResponse(array("status" => "failure", "message" => "Please sign in"));
        }

        if (!($response = $this->getStoredResponse($requestSlug, $responseAuthor))) {
            return new JsonResponse(array("status" => "failure", "message" => "Photo could not be found"));
        }

        if ($rating = $this->getStoredRating($user, $response)) {
            if ($rating->getRate() == $rate) {
                return new JsonResponse(array("status" => "success"));
            }
            /*Updating rating*/
            $rating->setRate($rate);
            $em->persist($rating);
            $em->flush();

            return new JsonResponse(array("status" => "success"));
        }

        /* Creating new rating */
        $rating = new Rating();
        $rating->setUser($user)
            ->setResponse($response)
            ->setRate($rate);

        try {
            $em->persist($rating);
            $em->flush();
        } catch (\Exception $e) {
            return new JsonResponse(array("status" => "success", "message"=>"Internal server error"));
        }

        return new JsonResponse(array("status" => "success"));
    }

    /**
     * Removes Rating entity.
     *
     * @Route("/remove", name="showphoto_rating_remove")
     * @Method("POST")
     */
    public function removeAction(Request $request)
    {
        $requestSlug = $request->get('request_slug', '');
        $responseAuthor = $request->get('response_author', '');

        $em = $this->getDoctrine()->getEntityManager();

        if (!($user = $this->getUser())) {
            return new JsonResponse(array("status" => "failure", "message" => "Please sign in"));
        }

        if (!($response = $this->getStoredResponse($requestSlug, $responseAuthor))) {
            return new JsonResponse(array("status" => "failure", "message" => "Photo could not be found"));
        }

        if ($rating = $this->getStoredRating($user, $response)) {
            try {
                $em->remove($rating);
                $em->flush();
            } catch (\Exception $e) {
                throw new InternalErrorException();
            }
        };

        //$this->get('event_dispatcher')->dispatch(RatingEvents::RATE_PHOTO, new FilterRatingEvent(new Rating()));

        return new JsonResponse(array("status" => "success"));
    }

    private function getStoredResponse($requestSlug, $responseAuthor)
    {
        $em = $this->getDoctrine()->getManager();

        $request = $em->getRepository("AuderoShowphotoBundle:PhotoRequest")->findOneBy(array('slug' => $requestSlug));
        if (!$request) {
            return null;
        }

        $author = $em->getRepository("AuderoShowphotoBundle:User")->findOneBy(array('username' => $responseAuthor));
        if (!$author) {
            return null;
        }

        $response = $em->getRepository("AuderoShowphotoBundle:PhotoResponse")->findOneBy(array('request' => $request, 'user' => $author));
        if (!$response) {
            return null;
        }

        return $response;
    }


    /**
     * @param User $user
     * @param PhotoResponse $response
     * @return Rating|null
     */
    private function getStoredRating(User $user, PhotoResponse $response)
    {
        $em = $this->getDoctrine()->getManager();

        $rating = $em->getRepository("AuderoShowphotoBundle:Rating")->findOneBy(array('response' => $response, 'user' => $user));
        if (!$rating) {
            return null;
        }

        return $rating;
    }
}

<?php

namespace Audero\ShowphotoBundle\Controller;

use Audero\ShowphotoBundle\Entity\Rating;
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
     * @Route("/create", name="rating_create")
     * @Method("POST")
     */
    public function createAction(Request $request)
    {
        if($user = $this->getUser()) {
            $entity = new Rating();

            $photoId = $request->get('photo_id');
            $rate = $request->get('rate') == 1 ? 1 : 0;


            $response = $this->getDoctrine()->getRepository("AuderoShowphotoBundle:PhotoResponse")->findOneByPhotoId($photoId);
            if(!$response) {
                return new JsonResponse("Photo Response not found");
            }

            $em = $this->getDoctrine()->getManager();
            $rating = $this->getDoctrine()->getRepository("AuderoShowphotoBundle:Rating")->findOneBy(array('response'=>$response, 'user'=>$user->getId()));
            if($rating) {
                if($rating->getRate() == $rate) {
                    $em->remove($rating);
                }else{
                    $rating->setRate($rate);
                }
            }else{
                $rating = new Rating();
                $rating->setUser($user)
                       ->setResponse($response)
                       ->setRate($rate);

                $em->persist($rating);
            }

            $em->flush();

            return new JsonResponse('success');
        }

        return new JsonResponse("Please sign in");
    }
}

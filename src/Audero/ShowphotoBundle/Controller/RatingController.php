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

            $slug = $request->get('slug');
            $nr = $request->get('nr');
            $rate = $request->get('rate') == 1 ? 1 : 0;

            $request = $this->getDoctrine()->getRepository("AuderoShowphotoBundle:PhotoRequest")->findOneBySlug($slug);
            if(!$request) {
                return new JsonResponse("Photo Request not found");
            }

            $response = $this->getDoctrine()->getRepository("AuderoShowphotoBundle:PhotoResponse")->findOneByNr($request, $nr);
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

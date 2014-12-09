<?php

namespace Audero\WebBundle\Controller;


use Audero\ShowphotoBundle\Entity\PhotoResponse;
use Audero\ShowphotoBundle\Entity\Rating;
use Audero\ShowphotoBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\CssSelector\Exception\InternalErrorException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Load controller.
 *
 * @Route("/load")
 */
class LoadController extends Controller
{
    const LOAD_RESPONSES = 10;

    /**
     * @Route("/newest", name="web_load_newest")
     * @Method("POST")
     */
    public function newestAction(Request $request)
    {
        $offset = $request->request->get('offset', 0);
        $repo = $this->getDoctrine()->getRepository("AuderoShowphotoBundle:PhotoResponse");
        $responses = $repo->findNewestWithOffset($offset, LoadController::LOAD_RESPONSES);
        if(!$responses) {
            return new Response();
        }
        $ratings = array();
        if($user = $this->getUser()) {
            $ratings = $this->getUserRatingsForResponses($user, $responses);
        }

        $html = $this->renderView('AuderoWebBundle:Load:responses.html.twig', array(
            'responses' => $responses,
            'ratings'   => $ratings
        ));

        return new Response($html);
    }

    /**
     * @Route("/most-commented", name="web_load_mostCommented")
     * @Method("POST")
     */
    public function mostCommentedAction()
    {
        //$offset = $request->query->get('offset', 0);
        $repo = $this->getDoctrine()->getRepository("AuderoShowphotoBundle:PhotoRequest");
        $requests = $repo->findAll();

        $html = $this->renderView('AuderoWebBundle:Load:responses.html.twig', array(
            'requests' => $requests
        ));

        return new Response($html);
    }

    /**
     * @Route("/best", name="web_load_best")
     * @Method("POST")
     */
    public function bestAction() {

    }

    private function getUserRatingsForResponses(User $user, $responses) {
        $repo = $this->getDoctrine()->getRepository("AuderoShowphotoBundle:Rating");
        if(!$repo) {
            throw new InternalErrorException();
        }

        $ratings = array();
        foreach((array) $responses as $response) {
            // TODO BIG CHECKING
            if($response instanceof PhotoResponse) {
                $rate = $repo->findOneBy(array('user'=>$user, 'response'=>$response));
                if($rate && $rate instanceof Rating) {
                    $ratings[$response->getId()] = $rate->getRate();
                }
            }
        }

        return $ratings;
    }



}

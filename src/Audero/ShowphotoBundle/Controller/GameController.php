<?php

namespace Audero\ShowphotoBundle\Controller;

use Audero\ShowphotoBundle\Entity\PhotoRequest;
use Audero\ShowphotoBundle\Entity\PhotoResponse;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Audero\ShowphotoBundle\Form\PhotoRequestType;
use Audero\ShowphotoBundle\Form\PhotoResponseType;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class GameController extends Controller
{
    /**
     * @Route("/play", name="showphoto_play")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        if($user = $this->getUser()) {

            // adding user to players list
            if(!($player = $user->getPlayer())) {
                $player = $this->get('game.player.manager')->add($user);
            }

            // if user was successfully added to players list
            if($player) {

                $requests = $em->getRepository('AuderoShowphotoBundle:PhotoRequest')->findAll();
                $responses = $em->getRepository('AuderoShowphotoBundle:PhotoResponse')->findAll();
                $request = new PhotoRequest();
                $formRequest = $this->createFormRequest($request);
                $response = new PhotoResponse();
                $formResponse = $this->createFormResponse($response);

                return array(
                    'form_request'   => $formRequest->createView(),
                    'form_response'   => $formResponse->createView(),
                    'requests' => $requests,
                    'responses' => $responses
                );
            }

            return $this->redirect($this->generateUrl('showphoto_spectate'));
        }

        throw new AccessDeniedException();
    }

    /**
     * @Route("/game", name="audero_game_timeLeft")
     * @Template()
     */
    public function createRequestAction()
    {
        $em = $this->getDoctrine()->getManager();
        $requests = $em->getRepository('AuderoShowphotoBundle:PhotoRequest');
    }

    /**
     * @Route("/game", name="audero_game_timeLeft")
     * @Template()
     */
    public function createResponseAction()
    {
        $em = $this->getDoctrine()->getManager();
        $requests = $em->getRepository('AuderoShowphotoBundle:PhotoRequest');
    }

    /**
     * @Route("/game", name="audero_game_timeLeft")
     * @Template()
     */
    public function timeLeftAction()
    {
        $em = $this->getDoctrine()->getManager();
        $requests = $em->getRepository('AuderoShowphotoBundle:PhotoRequest');
    }

    /**
     * @Route("/game", name="audero_game_timeLeft")
     * @Template()
     */
    public function lastRequestAction()
    {
        $em = $this->getDoctrine()->getManager();
        $requests = $em->getRepository('AuderoShowphotoBundle:PhotoRequest');
    }


    private function createFormRequest(PhotoRequest $entity)
    {
        $form = $this->createForm(new PhotoRequestType(), $entity, array(
            'action' => $this->generateUrl('request_create'),
            'method' => 'POST',
        ));

        return $form;
    }

    private function createFormResponse(PhotoResponse $entity)
    {
        $form = $this->createForm(new PhotoResponseType(), $entity, array(
            'action' => $this->generateUrl('game_response_create'),
            'method' => 'POST',
        ));

        return $form;
    }
}
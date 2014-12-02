<?php

namespace Audero\ShowphotoBundle\Controller;

use Audero\ShowphotoBundle\Entity\PhotoRequest;
use Audero\ShowphotoBundle\Entity\PhotoResponse;
use Guzzle\Http\Exception\ClientErrorResponseException;
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

            // if user could not be added
            if(!$player) {
                return $this->redirect($this->generateUrl('showphoto_spectate'));
            }

            $request = $em->getRepository('AuderoShowphotoBundle:PhotoRequest')->findNewest();
            if(!$request) {
                // TODO ??
                throw new ClientErrorResponseException();
            }
            $responses = (array) $em->getRepository('AuderoShowphotoBundle:PhotoResponse')->findByRequest($request);
            $formResponse = $this->createFormResponse(new PhotoResponse());

            return array(
                'form_response'   => $formResponse->createView(),
                'request' => $request,
                'responses' => $responses
            );

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
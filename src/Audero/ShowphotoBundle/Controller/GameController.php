<?php

namespace Audero\ShowphotoBundle\Controller;

use Audero\ShowphotoBundle\Entity\PhotoRequest;
use Audero\ShowphotoBundle\Entity\PhotoResponse;
use Audero\ShowphotoBundle\Entity\Wish;
use Guzzle\Http\Exception\ClientErrorResponseException;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Audero\ShowphotoBundle\Form\PhotoRequestType;
use Audero\ShowphotoBundle\Form\PhotoResponseType;
use Symfony\Component\CssSelector\Exception\InternalErrorException;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class GameController extends Controller
{
    /**
     * @Route("/play", name="showphoto_play")
     * @Template()
     */
    public function indexAction()
    {
        if(!($user = $this->getUser())) {
            throw new AccessDeniedException();
        }

        $em = $this->getDoctrine()->getManager();

        // adding user to players list
        if(!($player = $user->getPlayer())) {
            $player = $this->get('game.player.manager')->add($user);
        }

        // if user could not be added to players list
        if(!$player) {
            return $this->redirect($this->generateUrl('showphoto_spectate_index'));
        }

        /** @var \Audero\BackendBundle\Entity\Options $backendOptions */
        $backendOptions = $em->getRepository('AuderoBackendBundle:OptionsRecord')->findCurrent();
        if(!$backendOptions) {
            throw new InternalErrorException();
        }

        $wishes = $em->getRepository('AuderoShowphotoBundle:Wish')->findBy(array('user'=>$user));
        $wishList = array();
        /**@var Wish $wish*/
        foreach($wishes as $wish) {
            $wishList[$wish->getPosition()] = $wish;
        }

        $players = $em->getRepository('AuderoShowphotoBundle:Player')->findAllOrderedByRank();
        $request = $em->getRepository('AuderoShowphotoBundle:PhotoRequest')->findNewest();
        if(!$request) {
            throw new InternalErrorException();
        }
        $responses = $em->getRepository('AuderoShowphotoBundle:PhotoResponse')->findByRequest($request);
        $formResponse = $this->createFormResponse(new PhotoResponse());

        return array(
            'form_response'   => $formResponse->createView(),
            'request' => $request,
            'responses' => $responses,
            'players' => $players,
            'wishList' => $wishList,
            'wishListSize' => $backendOptions->getPlayerWishesCount()
        );
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
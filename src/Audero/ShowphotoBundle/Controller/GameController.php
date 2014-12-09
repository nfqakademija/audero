<?php

namespace Audero\ShowphotoBundle\Controller;

use Audero\ShowphotoBundle\Entity\PhotoRequest;
use Audero\ShowphotoBundle\Entity\PhotoResponse;
use Audero\ShowphotoBundle\Entity\Wish;
use Audero\ShowphotoBundle\Form\PhotoResponseFileType;
use Audero\ShowphotoBundle\Form\PhotoResponseUrlType;
use Guzzle\Http\Exception\ClientErrorResponseException;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Audero\ShowphotoBundle\Form\PhotoResponseType;
use Symfony\Component\CssSelector\Exception\InternalErrorException;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

/**
 * Game controller.
 *
 * @Route("/game")
 */
class GameController extends Controller
{
    /**
     * @Route("/", name="showphoto_game_play")
     * @Template()
     */
    public function playAction()
    {
        if(!($user = $this->getUser())) {
            throw new AccessDeniedException();
        }

        $em = $this->getDoctrine()->getManager();

        // adding user to players list
        if(!($player = $user->getPlayer())) {
            $player = $this->get('game.player')->add($user);
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

        $players = $em->getRepository('AuderoShowphotoBundle:Player')->findAllOrderedByRate();
        $request = $em->getRepository('AuderoShowphotoBundle:PhotoRequest')->findOneNewest();
        if(!$request) {
            throw new InternalErrorException();
        }

        $responses = $em->getRepository('AuderoShowphotoBundle:PhotoResponse')->findByRequest($request);

        $formFile = $this->createForm(new PhotoResponseFileType(), null, array(
            'action' => $this->generateUrl('game_response_file'),
            'method' => 'POST',
        ));
        $formUrl = $this->createForm(new PhotoResponseUrlType(), null, array(
            'action' => $this->generateUrl('game_response_url'),
            'method' => 'POST',
        ));

        return array(
            'form_file' => $formFile->createView(),
            'form_url' => $formUrl->createView(),
            'request' => $request,
            'validUntil' => $this->get('game.photo.request')->getValidUntil($request),
            'responses' => $responses,
            'players' => $players,
            'wishList' => $wishList,
            'wishListSize' => $backendOptions->getPlayerWishesCount()
        );
    }

    /**
     * @Route("/spectate", name="showphoto_game_spectate")
     * @Template()
     */
    public function spectateAction() {
        return array();
    }


}
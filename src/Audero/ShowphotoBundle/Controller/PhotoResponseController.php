<?php

namespace Audero\ShowphotoBundle\Controller;

use Audero\ShowphotoBundle\Entity\PhotoResponse;
use Audero\ShowphotoBundle\Form\PhotoResponseType;
use Symfony\Component\CssSelector\Exception\InternalErrorException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Cocur\Slugify\Slugify;

/**
 * PhotoResponse controller.
 *
 * @Route("/game/response")
 */
class PhotoResponseController extends Controller
{
    /**
     * Creates a new Response entity.
     *
     * @Route("/create", name="game_response_create")
     * @Method("POST")
     */
    public function createAction(Request $request)
    {
        if (!$this->get('game.player.manager')->isPlayer($this->getUser())) {
            throw new AccessDeniedException();
        }

        try {
            $response = $this->get('game.photo.response')->handlePhotoResponse($request);
        } catch (\Exception $e) {
            return new JsonResponse(array('status' => 'failure', 'message' => $e->getMessage()));
        }

        $em = $this->getDoctrine()->getManager();
        try {
            $em->persist($response);
            $em->flush();
        } catch (\Exception $e) {
            throw new InternalErrorException();
        }

        // Broadcasting
        $this->get('game.photo.response')->broadcast($response);

        return new JsonResponse(array('status' => 'success'));
    }
}

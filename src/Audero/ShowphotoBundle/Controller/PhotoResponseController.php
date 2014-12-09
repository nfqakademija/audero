<?php

namespace Audero\ShowphotoBundle\Controller;

use Audero\ShowphotoBundle\Entity\PhotoResponse;
use Audero\ShowphotoBundle\Form\PhotoResponseFileType;
use Audero\ShowphotoBundle\Form\PhotoResponseType;
use Audero\ShowphotoBundle\Form\PhotoResponseUrlType;
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
 * @Route("/response")
 */
class PhotoResponseController extends Controller
{
    /**
     * Creates a new Response entity.
     *
     * @Route("/file", name="game_response_file")
     * @Method("POST")
     * @param Request $request
     * @return JsonResponse
     */
    public function fileAction(Request $request)
    {
        if (!$this->get('game.player')->isPlayer($this->getUser())) {
            throw new AccessDeniedException();
        }

        $entity = new PhotoResponse();
        $form = $this->createForm(new PhotoResponseFileType(), $entity);
        $form->handleRequest($request);
        if (!$form->isValid()) {
            // TODO fix error messages
            return new JsonResponse(array('status' => 'failure', "message" => $form->getErrors()));
        }

        return $this->handlePhotoResponse($entity);
    }

    /**
     * Creates a new Response entity.
     *
     * @Route("/url", name="game_response_url")
     * @Method("POST")
     * @param Request $request
     * @return JsonResponse
     */
    public function urlAction(Request $request)
    {
        if (!$this->get('game.player')->isPlayer($this->getUser())) {
            throw new AccessDeniedException();
        }

        $entity = new PhotoResponse();
        $form = $this->createForm(new PhotoResponseUrlType(), $entity);
        $form->handleRequest($request);
        if (!$form->isValid()) {
            // TODO fix error messages
            return new JsonResponse(array('status' => 'failure', "message" => $form->getErrors()));
        }

        return $this->handlePhotoResponse($entity);
    }

    /**
     * @param PhotoResponse $entity
     * @return JsonResponse
     */
    private function handlePhotoResponse(PhotoResponse $entity)
    {
        try {
            $response = $this->get('game.photo.response')->manage($entity);
        } catch (\Exception $e) {
            return new JsonResponse(array('status' => 'failure', 'message' => $e->getMessage()));
        }

        $em = $this->getDoctrine()->getManager();
        $em->persist($response);
        $em->flush();

        // Broadcasting
        $this->get('game.photo.response')->broadcast($response);

        return new JsonResponse(array('status' => 'success'));
    }
}

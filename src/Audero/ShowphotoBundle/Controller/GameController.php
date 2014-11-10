<?php

namespace Audero\ShowphotoBundle\Controller;

use Audero\ShowphotoBundle\Entity\PhotoRequest;
use Audero\ShowphotoBundle\Entity\PhotoResponse;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Audero\ShowphotoBundle\Form\PhotoRequestType;
use Audero\ShowphotoBundle\Form\PhotoResponseType;

class GameController extends Controller
{
    /**
     * @Route("/game", name="audero_game")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
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

    private function createFormRequest(PhotoRequest $entity)
    {
        $form = $this->createForm(new PhotoRequestType(), $entity, array(
            'action' => $this->generateUrl('request_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    private function createFormResponse(PhotoResponse $entity)
    {
        $form = $this->createForm(new PhotoResponseType(), $entity, array(
            'action' => $this->generateUrl('game_response_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }
}
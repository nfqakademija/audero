<?php

namespace Audero\ShowphotoBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Audero\ShowphotoBundle\Entity\Application;
use Audero\ShowphotoBundle\Entity\Interpretation;
use Audero\ShowphotoBundle\Form\ApplicationType;
use Audero\ShowphotoBundle\Form\InterpretationType;

class GameController extends Controller
{
    /**
     * @Route("/game", name="audero_game")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $requests = $em->getRepository('AuderoShowphotoBundle:Application')->findAll();
        $responses = $em->getRepository('AuderoShowphotoBundle:Interpretation')->findAll();
        $request = new Application();
        $formRequest = $this->createFormRequest($request);
        $response = new Interpretation();
        $formResponse = $this->createFormResponse($response);


        return array(
            'form_request'   => $formRequest->createView(),
            'form_response'   => $formResponse->createView(),
            'requests' => $requests,
            'responses' => $responses
        );
    }


    private function createFormResponse(Interpretation $entity)
    {
        $form = $this->createForm(new InterpretationType(), $entity, array(
            'action' => $this->generateUrl('game_response_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    private function createFormRequest(Application $entity)
    {
        $form = $this->createForm(new ApplicationType(), $entity, array(
            'action' => $this->generateUrl('request_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

}

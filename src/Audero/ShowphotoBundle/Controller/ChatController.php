<?php

namespace Audero\ShowphotoBundle\Controller;

use Audero\ShowphotoBundle\Entity\User;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Audero\ShowphotoBundle\Entity\ChatMessage;
use Audero\ShowphotoBundle\Form\ChatType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

/**
 * Class ChatController
 * @package Audero\ShowphotoBundle\Controller
 */
class ChatController extends Controller
{
    /**
     * @Route("/chat", name="showphoto_chat_index")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $messages = $em->getRepository("AuderoShowphotoBundle:ChatMessage")->findAll();
        $form = $this->createForm(new ChatType(), new ChatMessage(), array(
            "action" => $this->generateUrl('showphoto_chat_postMessage')
        ));
        return array(
            'messages' => $messages,
            'chat' => $form->createView()
        );
    }

    /**
     * @Route("/chat", name="showphoto_chat_postMessage")
     * @Method("POST")
     * @Template()
     */
    public function postMessageAction(Request $request)
    {
        $user = $this->getUser();
        if(!$this->get('game.player')->isPlayer($user)) {
            throw new AccessDeniedException();
        }

        $message = new ChatMessage();
        $form = $this->createForm(new ChatType(), $message);

        $form->handleRequest($request);
        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $message->setUser($user);
            $em->persist($message);
            $em->flush();

            $this->get('game.chat')->broadcast($message);

            return new JsonResponse(json_encode(array("status" => "success")));
        }

        return new JsonResponse(json_encode(array("status" => "failure", "message" =>$form->getErrors())));
    }
}

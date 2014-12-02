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
     * @Route("/chat", name="showphoto_chat")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        if (!$this->get('game.player.manager')->isPlayer($this->getUser())) {
            throw new AccessDeniedException();
        }

        $em = $this->getDoctrine()->getManager();
        $messages = $em->getRepository("AuderoShowphotoBundle:ChatMessage")->findAll();
        $form = $this->createForm(new ChatType(), new ChatMessage(), array(
            "action" => $this->generateUrl('showphoto_chat_post_message')
        ));
        return array(
            'messages' => $messages,
            'chat' => $form->createView()
        );
    }

    /**
     * @Route("/chat", name="showphoto_chat_post_message")
     * @Method("POST")
     * @Template()
     */
    public function postMessageAction(Request $request)
    {
        $user = $this->getUser();
        if(!$this->get('game.player.manager')->isPlayer($user)) {
            throw new AccessDeniedException();
        }

        $message = new ChatMessage();
        $form = $this->createForm(new ChatType(), $message);

        $form->handleRequest($request);
        $response = new JsonResponse();

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $message->setUser($user);
            $em->persist($message);
            $em->flush();

            $data = array(
                'command' => 'push',
                'data' => array(
                    'topic' => "chat",
                    'data'    => array(
                        'user' => $user->getUsername(),
                        'text' => $message->getText(),
                    )
                )
            );

            $context = new \ZMQContext();
            $socket = $context->getSocket(\ZMQ::SOCKET_PUSH, 'my pusher');
            $socket->connect("tcp://127.0.0.1:5555");
            $socket->send(json_encode($data));

            $response->setData(array("status" => "success"));
            return $response;
        }

        $response->setData(array("status" => "failure", "message" =>$form->getErrors()));
        return $response;
    }
}

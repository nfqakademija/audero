<?php

namespace Audero\WebBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class MainController extends Controller
{
    public function indexAction()
    {
        var_dump($this->getUser()); die;

        $messages = $user->getChatMessages();

        foreach($messages as $message) {
            var_dump($message->getText());
        }

        return $this->render('AuderoWebBundle:Main:index.html.twig', array(

        ));    }
}

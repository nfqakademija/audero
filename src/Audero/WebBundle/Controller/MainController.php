<?php

namespace Audero\WebBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class MainController extends Controller
{
    public function indexAction()
    {
        return $this->render('AuderoWebBundle:Main:index.html.twig', array(
                // ...
            ));
    }

}

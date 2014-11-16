<?php

namespace Audero\WebBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class MainController extends Controller
{
    public function indexAction()
    {
        var_dump($this->getDoctrine()->getManager()->getRepository("AuderoBackendBundle:OptionsRecord")->findCurrent());
        die;

        return $this->render('AuderoWebBundle:Main:index.html.twig', array(

        ));    }
}

<?php

namespace Audero\WebBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class MainController extends Controller
{
    public function indexAction()
    {
        $uploader = $this->get('photo.uploader');
        //$uploader->uploadFromUrl("asasas");

        return $this->render('AuderoWebBundle:Main:index.html.twig', array(

        ));    }
}

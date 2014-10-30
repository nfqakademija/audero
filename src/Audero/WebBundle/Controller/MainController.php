<?php

namespace Audero\WebBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class MainController extends Controller
{
    public function indexAction()
    {
        $pm = $this->get('audero.photos.uploader');


        return $this->render('AuderoWebBundle:Main:index.html.twig', array(
                // ...
            ));
    }

}

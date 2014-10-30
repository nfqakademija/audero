<?php

namespace Audero\WebBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class MainController extends Controller
{
    public function indexAction()
    {
        $uploader = $this->get('audero.photos.uploader');
        $uploader->uploadFromUrl('http://img.memerial.net/memerial.net/25/can-we-fix-it.jpg');

        return $this->render('AuderoWebBundle:Main:index.html.twig', array(
                // ...
            ));
    }

}

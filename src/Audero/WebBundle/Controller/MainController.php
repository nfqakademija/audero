<?php

namespace Audero\WebBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class MainController extends Controller
{
    public function indexAction()
    {
        $uploader = $this->get('audero.photos.uploader');
        $uploader->uploadFromUrl('http://www.funny.funta.in/wp-content/uploads/2014/08/funny-horse-pictures-1.jpg');

        return $this->render('AuderoWebBundle:Main:index.html.twig', array(
                // ...
            ));
    }

}

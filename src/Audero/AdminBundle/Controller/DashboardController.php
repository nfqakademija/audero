<?php

namespace Audero\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DashboardController extends Controller
{
    public function indexAction()
    {
        return $this->render('AuderoAdminBundle:Dashboard:index.html.twig', array(
                // ...
            ));    }

}

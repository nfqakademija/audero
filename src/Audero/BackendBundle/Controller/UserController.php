<?php

namespace Audero\BackendBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Audero\BackendBundle\Entity\User;

class UserController extends Controller
{
    public function indexAction()
    {
        // Fix
        $users = $this->get('fos_user.user_manager')->findUsers();
        return $this->render('AuderoBackendBundle:User:index.html.twig', array(
            'users' => $users
        ));
    }

}

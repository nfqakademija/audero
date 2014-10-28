<?php

namespace Audero\BackendBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

/**
 * Applications controller.
 *
 * @Route("/admin/requests")
 */
class ApplicationController extends Controller
{
    /**
     * Lists all Applications.
     *
     * @Route("/", name="audero_backend_requests")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('AuderoShowphotoBundle:Application')->findAll();

        return $this->render('AuderoBackendBundle:Application:index.html.twig',array(
            'entities' => $entities,
        ));
    }


}

<?php

namespace Audero\BackendBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * Dashboard controller.
 *
 * @Route("/admin")
 */
class DashboardController extends Controller
{
    /**
     * @Route("/", name="backend_dashboard_index")
     * @Template()
     */
    public function indexAction()
    {
        return $this->render('AuderoBackendBundle:Dashboard:index.html.twig', array(

        ));
    }


}

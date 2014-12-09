<?php

namespace Audero\BackendBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * Request controller.
 *
 * @Route("/admin/requests")
 */
class RequestController extends Controller
{
    /**
     * @Route("/", name="backend_request_index")
     * @Template()
     */
    public function indexAction()
    {
        return array();
    }


}

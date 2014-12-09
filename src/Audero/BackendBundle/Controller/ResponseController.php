<?php

namespace Audero\BackendBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * Response controller.
 *
 * @Route("/admin/responses")
 */
class ResponseController extends Controller
{
    /**
     * @Route("/", name="backend_response_index")
     * @Template()
     */
    public function indexAction()
    {
        return array();
    }


}

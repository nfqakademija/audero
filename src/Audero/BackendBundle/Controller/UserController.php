<?php

namespace Audero\BackendBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\CssSelector\Exception\InternalErrorException;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * PhotoResponse controller.
 *
 * @Route("/admin/users")
 */
class UserController extends Controller
{
    /**
     * Returns json data
     *
     * @Route("/", name="backend_user_index")
     * @Template()
     */
    public function indexAction()
    {
        return array();
    }

    /**
     * Returns json data
     *
     * @Route("/data", name="backend_user_data")
     */
    public function dataAction() {
        $repo = $this->getDoctrine()->getRepository('AuderoShowphotoBundle:User');
        if(!$repo) {
            throw new InternalErrorException();
        }

        return new JsonResponse($repo->findAllData());
    }

}

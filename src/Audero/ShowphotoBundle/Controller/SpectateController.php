<?php

namespace Audero\ShowphotoBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class SpectateController extends Controller
{
    /**
     * @Route("/spectate", name="showphoto_spectate")
     * @Template()
     */
    public function indexAction()
    {
        return array(
                // ...
            );    }

}

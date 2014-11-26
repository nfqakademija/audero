<?php

namespace Audero\WebBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Cocur\Slugify\Slugify;
/**
 * Page controller.
 *
 * @Route("/")
 */
class PageController extends Controller
{
    /**
     * Displays photos-responses
     *
     * @Route("/", name="web_index")
     * @Template()
     */
    public function indexAction()
    {
        $requests = $this->getDoctrine()->getManager()->getRepository("AuderoShowphotoBundle:PhotoRequest")->findAll();
        return $this->render('AuderoWebBundle:Page:index.html.twig', array(
            'requests' => $requests
        ));
    }
}

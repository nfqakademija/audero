<?php

namespace Audero\WebBundle\Controller;

use Audero\ShowphotoBundle\Entity\PhotoResponse;
use Audero\ShowphotoBundle\Entity\Rating;
use Audero\ShowphotoBundle\Event\FilterRatingEvent;
use Audero\ShowphotoBundle\RatingEvents;
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
     * @Route("/", name="web_page_index")
     * @Template()
     */
    public function indexAction()
    {
        $requests = $this->getDoctrine()->getRepository("AuderoShowphotoBundle:PhotoRequest")->findAll();
        return $this->render('AuderoWebBundle:Page:index.html.twig', array(
            'requests' => $requests
        ));
    }

    /**
     * Displays request with it's photos
     *
     * @Route("/game/{slug}", name="web_page_showRequest")
     * @Template()
     */
    public function showRequestAction($slug)
    {
        $request = $this->getDoctrine()->getRepository("AuderoShowphotoBundle:PhotoRequest")->findOneBy(array('slug'=>$slug));
        if(!$request) {
            return $this->redirect($this->generateUrl("web_page_index"));
        }

        return $this->render('AuderoWebBundle:Page:request.html.twig', array(
            'request' => $request
        ));
    }

    /**
     * Displays single response
     *
     * @Route("/game/{slug}/{author}", name="web_page_showResponse")
     * @Template()
     */
    public function showResponseAction($slug, $author)
    {
        $request = $this->getDoctrine()->getRepository("AuderoShowphotoBundle:PhotoRequest")->findOneBy(array('slug'=>$slug));
        if(!$request) {
            return $this->redirect($this->generateUrl("web_page_index"));
        }

        $author = $this->getDoctrine()->getRepository("AuderoShowphotoBundle:User")->findOneBy(array('username'=>$author));
        if(!$author) {
            return $this->redirect($this->generateUrl("web_page_showRequest"));
        }

        $response = $this->getDoctrine()->getRepository("AuderoShowphotoBundle:PhotoResponse")->findOneBy(array('request'=>$request, 'user'=>$author));
        if(!$response) {
            return $this->redirect($this->generateUrl("web_page_showRequest"));
        }

        return $this->render('AuderoWebBundle:Page:response.html.twig', array(
            'response' => $response
        ));
    }




}

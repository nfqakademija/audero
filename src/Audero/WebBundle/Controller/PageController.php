<?php

namespace Audero\WebBundle\Controller;

use Audero\ShowphotoBundle\Entity\PhotoResponse;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * Page controller.
 *
 * @Route("/")
 */
class PageController extends Controller
{
    /**
     * @Route("/", name="web_page_index")
     * @Template()
     */
    public function indexAction()
    {
        return $this->forward("AuderoWebBundle:Page:newest");
    }

    /**
     * @Route("/newest", name="web_page_newest")
     * @Template()
     */
    public function newestAction()
    {
        return array();
    }

    /**
     * @Route("/most-commented", name="web_page_mostCommented")
     * @Template()
     */
    public function mostCommentedAction()
    {
        return array();
    }

    /**
     * @Route("/best", name="web_page_best")
     * @Template()
     */
    public function bestAction()
    {
        return array();
    }

    /**
     * Displays single photo request
     *
     * @Route("/game/{slug}", name="web_page_singleRequest")
     * @Template()
     */
    public function singleRequestAction($slug)
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
     * Displays single photo response
     *
     * @Route("/game/{slug}/{author}", name="web_page_singleResponse")
     * @Template()
     */
    public function singleResponseAction($slug, $author)
    {
        $request = $this->getDoctrine()->getRepository("AuderoShowphotoBundle:PhotoRequest")->findOneBy(array('slug'=>$slug));
        if(!$request) {
            return $this->redirect($this->generateUrl("web_page_index"));
        }

        $author = $this->getDoctrine()->getRepository("AuderoShowphotoBundle:User")->findOneBy(array('username'=>$author));
        if(!$author) {
            return $this->redirect($this->generateUrl("web_page_singleRequest"));
        }

        $response = $this->getDoctrine()->getRepository("AuderoShowphotoBundle:PhotoResponse")->findOneBy(array('request'=>$request, 'user'=>$author));
        if(!$response) {
            return $this->redirect($this->generateUrl("web_page_singleRequest"));
        }

        return $this->render('AuderoWebBundle:Page:response.html.twig', array(
            'response' => $response
        ));
    }
}

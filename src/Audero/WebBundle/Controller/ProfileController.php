<?php

namespace Audero\WebBundle\Controller;

use FOS\UserBundle\Controller\ProfileController as BaseController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\CssSelector\Exception\InternalErrorException;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class ProfileController extends BaseController
{
    /**
     * @Route("/profile", name="web_profile_index")
     */
    public function indexAction()
    {
        if(!($user = $this->getUser())) {
            throw new AccessDeniedException();
        }
        /** @var \Audero\BackendBundle\Entity\Options $options */
        $options = $this->getDoctrine()->getRepository('AuderoBackendBundle:OptionsRecord')->findCurrent();
        if(!$options) {
            throw new InternalErrorException();
        }

        $wishes = $this->getDoctrine()->getRepository('AuderoShowphotoBundle:Wish')->findBy(array('user'=>$user));
        $wishList = array();
        foreach($wishes as $wish) {
            $wishList[$wish->getPosition()] = $wish;
        }

        $repo = $this->getDoctrine()->getRepository("AuderoShowphotoBundle:PhotoResponse");
        if(!$repo) {
            throw new InternalErrorException("Could not get PhotoResponse repository");
        }
        $responses = $repo->findLatest($user, 3);

        $repo = $this->getDoctrine()->getRepository("AuderoShowphotoBundle:PhotoRequest");
        if(!$repo) {
            throw new InternalErrorException("Could not get PhotoRequest repository");
        }
        $requests = $repo->findLatest($user, 3);

        return $this->render('FOSUserBundle:Profile:show.html.twig', array(
            'wishList' => $wishList,
            'responses' => $responses,
            'requests'  => $requests,
            'wishListSize' => $options->getPlayerWishesCount(),
            'rank' => $this->getDoctrine()->getRepository("AuderoShowphotoBundle:user")->getRank($user)
        ));
    }

    /**
     * @Route("/user/{username}", name="web_profile_user")
     */
    public function userAction($username)
    {
        $user = $this->getDoctrine()->getRepository("AuderoShowphotoBundle:User")->findOneBy(array('username'=>$username));
        if(!$user) {
            return $this->redirect($this->generateUrl("web_page_index"));
        }

        $repo = $this->getDoctrine()->getRepository('AuderoBackendBundle:OptionsRecord');
        if(!$repo) {
            throw new InternalErrorException("Could not get OptionsRecord repository");
        }
        /** @var \Audero\BackendBundle\Entity\Options $options */
        $options = $repo->findCurrent();
        if(!$options) {
            throw new InternalErrorException("Could not get admin options");
        }

        $repo = $this->getDoctrine()->getRepository("AuderoShowphotoBundle:PhotoResponse");
        if(!$repo) {
            throw new InternalErrorException("Could not get PhotoResponse repository");
        }
        $responses = $repo->findLatest($user, 3);

        $repo = $this->getDoctrine()->getRepository("AuderoShowphotoBundle:PhotoRequest");
        if(!$repo) {
            throw new InternalErrorException("Could not get PhotoRequest repository");
        }
        $requests = $repo->findLatest($user, 3);

        return $this->render('AuderoWebBundle:Profile:user.html.twig', array(
            'user' => $user,
            'responses' => $responses,
            'requests'  => $requests,
            'rank' => $this->getDoctrine()->getRepository("AuderoShowphotoBundle:user")->getRank($user)
        ));
    }
}
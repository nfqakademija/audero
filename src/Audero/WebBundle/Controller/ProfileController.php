<?php

namespace Audero\WebBundle\Controller;

use Audero\ShowphotoBundle\Entity\Wish;
use Audero\WebBundle\Form\WishListType;
use Cocur\Slugify\Slugify;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\PersistentCollection;
use FOS\UserBundle\Controller\ProfileController as BaseController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class ProfileController extends BaseController
{
    /**
     * @Route("/profile", name="web_profile_show")
     */
    public function showAction()
    {
        if(!($user = $this->getUser())) {
            throw new AccessDeniedException();
        }

        $wishes = (array) $this->getDoctrine()->getRepository('AuderoShowphotoBundle:Wish')->findBy(array('user'=>$user), array('position'=>'ASC'));
        $wishList = array();
        foreach($wishes as $wish) {
            $wishList[$wish->getPosition()] = $wish;
        }
        return $this->render('FOSUserBundle:Profile:show.html.twig', array(
            'wishList' => $wishList,
            'wishListSize' => 10 //TODO GET FROM ADMIN
        ));
    }
}
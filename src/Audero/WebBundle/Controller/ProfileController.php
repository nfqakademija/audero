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
use Symfony\Component\CssSelector\Exception\InternalErrorException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
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
        return $this->render('FOSUserBundle:Profile:show.html.twig', array(
            'wishList' => $wishList,
            'wishListSize' => $options->getPlayerWishesCount(),
            'rank' => $this->getDoctrine()->getRepository("AuderoShowphotoBundle:user")->getRank($user)
        ));
    }
}
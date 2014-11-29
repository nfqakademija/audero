<?php

namespace Audero\WebBundle\Controller;

use Audero\ShowphotoBundle\Entity\Wish;
use Audero\WebBundle\Form\WishListType;
use FOS\UserBundle\Controller\ProfileController as BaseController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;

class ProfileController extends BaseController
{
    /**
     * @Route("/profile", name="web_profile_show")
     */
    public function showAction()
    {
        if($user = $this->getUser()) {

            $form = $this->createForm(new WishListType(), $user, array(
                'action' => $this->generateUrl('showphoto_wish_list_edit'),
            ));

            return $this->render('FOSUserBundle:Profile:show.html.twig', array(
                'user' => $user,
                'wish_list_form' => $form->createView()
            ));
        }

        return $this->redirect($this->generateUrl('fos_user_security_login'));
    }

    /**
     * @Route("/profile")
     * @Method("POST")
     * @Template()
     */
    public function editAction(Request $request)
    {
        return array(
                // ...
            );    }

}

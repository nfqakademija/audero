<?php

namespace Audero\ShowphotoBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Audero\WebBundle\Form\WishListType;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Collections\ArrayCollection;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

class WishListController extends Controller
{
    /**
     * @Route("/create")
     * @Template()
     */
    public function createAction()
    {
        return array(
                // ...
            );
    }

    /**
     * Edits user's wish list
     *
     * @Route("/wish/edit", name="showphoto_wish_list_edit")
     * @Method("POST")
     */
    public function editAction(Request $request)
    {
        $user = $this->getUser();

        if (!$user) {
            throw $this->createNotFoundException('Unable to find Application entity.');
        }

        $originalWishes = new ArrayCollection();
        foreach($user->getWishes() as $wish) {
            $originalWishes->add($wish);
        }

        $form = $this->createForm(new WishListType(), $user, array(
            'action' => $this->generateUrl('showphoto_wish_list_edit'),
        ));

        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            // removing previous wishes
            foreach($originalWishes as $wish) {
                if (false === $user->getWishes()->contains($wish)) {
                    // remove the Task from the Tag
                    $user->getTasks()->removeWish($wish);
                }
            }

            foreach($user->getWishes() as $wish) {
                $wish->setUser($user);
                $em->persist($wish);
            }

            $em->flush();
        }

        return $this->redirect($this->generateUrl('web_profile_show'));
    }

    /**
     * @Route("/remove")
     * @Template()
     */
    public function removeAction()
    {
        return array(
                // ...
            );    }

}

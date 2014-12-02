<?php

namespace Audero\ShowphotoBundle\Controller;

use Audero\ShowphotoBundle\Entity\Wish;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Audero\WebBundle\Form\WishListType;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Cocur\Slugify\Slugify;
use Doctrine\Common\Collections\ArrayCollection;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

/**
 * WishList controller.
 *
 * @Route("/wish")
 */
class WishListController extends Controller
{
    /**
     * @Route("/update", name="showphoto_wish_update")
     */
    public function updateAction(Request $request)
    {
        if(!($user = $this->getUser())) {
            return new JsonResponse(json_encode(array('status'=>'failure', 'message'=>'Please login')));
        }

        $title = (string) $request->request->get('title');
        $position = (int) $request->request->get('position');

        // checking if position is correct
        $maxWishes = 10;
        if($position < 1 || $position > $maxWishes ) {
            return new JsonResponse(json_encode(array('status'=>'failure', 'message'=>'Wrong position')));
        }

        // checking if wish has already been solved
        // TODO MOVE TO SERVICE
        $slugify = new Slugify();
        $slug = $slugify->slugify($title." ".$user->getUserName());
        //
        $em = $this->getDoctrine()->getManager();
        $request = $em->getRepository("AuderoShowphotoBundle:PhotoRequest")->findOneBy(array('slug'=>$slug));
        if($request) {
            return new JsonResponse(json_encode(array('status'=>'failure', 'message'=>'This wish is already ?? COMPLETED ??  ')));
        }

        // creating new wish

        // TODO MOVE TO SERVICE
        $slugify = new Slugify();
        $slug = $slugify->slugify($title);
        //
        $wish = new Wish();
        $wish->setTitle($title)
             ->setUser($user)
             ->setSlug($slug)
             ->setPosition($position);

        $validator = $this->get('validator');
        $errors = $validator->validate($wish);
        if(count($errors) > 0) {
            return new JsonResponse(json_encode(array('status'=>'failure', 'message'=>(string) $errors)));
        }

        // removing old wish
        $oldWish = $em->getRepository("AuderoShowphotoBundle:Wish")->findOneBy(array('user'=>$user, 'position'=>$position));
        if($oldWish) {
            $em->remove($oldWish);
            $em->flush();
        }

        $em->persist($wish);
        $em->flush();

        return new JsonResponse(json_encode('success'));
    }

    /**
     * @Route("/delete", name="showphoto_wish_delete")
     */
    public function deleteAction(Request $request) {
        if(!($user = $this->getUser())) {
            return new JsonResponse(json_encode(array('status'=>'failure', 'message'=>'Please login')));
        }

        $em = $this->getDoctrine()->getManager();

        $position = (int) $request->request->get('position');
        // TODO
        $maxWishes = 10;
        //
        if($position < 1 || $position > $maxWishes ) {
            return new JsonResponse(json_encode(array("status"=>"failure", "message"=>"Wrong position")));
        }

        $wish = $em->getRepository("AuderoShowphotoBundle:Wish")->findOneBy(array('user'=>$user, 'position'=>$position));
        if($wish) {
            $em->remove($wish);
            $em->flush();
        }

        return new JsonResponse(json_encode(array("status"=>"success")));
    }
}

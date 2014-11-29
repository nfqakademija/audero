<?php

namespace Audero\ShowphotoBundle\Controller;

use Audero\ShowphotoBundle\Entity\PhotoResponse;
use Audero\ShowphotoBundle\Form\PhotoResponseType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Cocur\Slugify\Slugify;

/**
 * PhotoResponse controller.
 *
 * @Route("/game/response")
 */
class PhotoResponseController extends Controller
{

    /**
     * Lists all Interpretation entities.
     *
     * @Route("/", name="game_response")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('AuderoShowphotoBundle:PhotoRequest')->findAll();

        return array(
            'entities' => $entities,
        );
    }
    /**
     * Creates a new Response entity.
     *
     * @Route("/", name="game_response_create")
     * @Method("POST")
     */
    public function createAction(Request $request)
    {
        $securityContext = $this->container->get('security.context');
        if (!$securityContext->isGranted('IS_AUTHENTICATED_FULLY')
            || !$this->get('game.player.manager')->isPlayer($this->getUser())) {
            throw new AccessDeniedException();
        }

        $entity = new PhotoResponse();
        $form = $this->createForm(new PhotoResponseType(), $entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $slugify = new Slugify();
            $uploader = $this->get('uploader');
            $response = json_decode($uploader->uploadFile($entity->getPhotoFile()));
            if ($response->status == 200) {
                $em = $this->getDoctrine()->getManager();
                $entity->setUser($securityContext->getToken()->getUser());
                $entity->setPhotoLink($response->data->link);
                $entity->setSlug($slugify->slugify(""));

                $em->persist($entity);
                $em->flush();

                $data = array(
                    'channel' => "game_response",
                    'data' => $entity->getPhoto()
                );

                $context = new \ZMQContext();
                $socket = $context->getSocket(\ZMQ::SOCKET_PUSH, 'my pusher');
                $socket->connect("tcp://127.0.0.1:5555");

                $socket->send(json_encode($data));

                return new Response('Success');
            }

            return new Response('Upload failed');
        }

        return new Response($form->getErrors());
    }

    /**
     * Displays a form to create a new Interpretation entity.
     *
     * @Route("/new", name="game_response_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $entity = new Interpretation();
        $form   = $this->createCreateForm($entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Finds and displays a Interpretation entity.
     *
     * @Route("/{id}", name="game_response_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AuderoShowphotoBundle:Interpretation')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Interpretation entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to edit an existing Interpretation entity.
     *
     * @Route("/{id}/edit", name="game_response_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AuderoShowphotoBundle:Interpretation')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Interpretation entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
    * Creates a form to edit a Interpretation entity.
    *
    * @param Interpretation $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Interpretation $entity)
    {
        $form = $this->createForm(new InterpretationType(), $entity, array(
            'action' => $this->generateUrl('game_response_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing Interpretation entity.
     *
     * @Route("/{id}", name="game_response_update")
     * @Method("PUT")
     * @Template("AuderoShowphotoBundle:Interpretation:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AuderoShowphotoBundle:Interpretation')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Interpretation entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('game_response_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }
    /**
     * Deletes a Interpretation entity.
     *
     * @Route("/{id}", name="game_response_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('AuderoShowphotoBundle:Interpretation')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Interpretation entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('game_response'));
    }

    /**
     * Creates a form to delete a Interpretation entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('game_response_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }
}

<?php

namespace Audero\ShowphotoBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Audero\ShowphotoBundle\Entity\Application;
use Audero\ShowphotoBundle\Form\ApplicationType;

/**
 * Application controller.
 *
 * @Route("/game/request")
 */
class ApplicationController extends Controller
{

    /**
     * Lists all Application entities.
     *
     * @Route("/", name="request")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('AuderoShowphotoBundle:Application')->findAll();

        return array(
            'entities' => $entities,
        );
    }

    /**
     * Creates a new Application entity.
     *
     * @Route("/", name="request_create")
     * @Method("POST")
     * @Template("AuderoShowphotoBundle:Application:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity = new Application();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            $entryData = array(
                'category' => "test1Category",
                'title'    => "request",
                'article'  => "labas",
                'when'     => "labas"
            );

            $context = new \ZMQContext();
            $socket = $context->getSocket(\ZMQ::SOCKET_PUSH, 'my pusher');
            $socket->connect("tcp://localhost:5555");

            $socket->send(json_encode($entryData));

            return $this->redirect($this->generateUrl('request_show', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Creates a form to create a Application entity.
     *
     * @param Application $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Application $entity)
    {
        $form = $this->createForm(new ApplicationType(), $entity, array(
            'action' => $this->generateUrl('request_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new Application entity.
     *
     * @Route("/new", name="request_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $entity = new Application();
        $form   = $this->createCreateForm($entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Finds and displays a Application entity.
     *
     * @Route("/{id}", name="request_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AuderoShowphotoBundle:Application')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Application entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to edit an existing Application entity.
     *
     * @Route("/{id}/edit", name="request_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AuderoShowphotoBundle:Application')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Application entity.');
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
    * Creates a form to edit a Application entity.
    *
    * @param Application $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Application $entity)
    {
        $form = $this->createForm(new ApplicationType(), $entity, array(
            'action' => $this->generateUrl('request_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing Application entity.
     *
     * @Route("/{id}", name="request_update")
     * @Method("PUT")
     * @Template("AuderoShowphotoBundle:Application:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AuderoShowphotoBundle:Application')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Application entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('request_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }
    /**
     * Deletes a Application entity.
     *
     * @Route("/{id}", name="request_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('AuderoShowphotoBundle:Application')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Application entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('request'));
    }

    /**
     * Creates a form to delete a Application entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('request_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }
}

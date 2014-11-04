<?php

namespace Audero\ShowphotoBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Audero\ShowphotoBundle\Entity\Judgement;
use Audero\ShowphotoBundle\Form\JudgementType;

/**
 * Judgement controller.
 *
 * @Route("/judgement")
 */
class JudgementController extends Controller
{
    /**
     * Lists all Judgement entities.
     *
     * @Route("/", name="judgement")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('AuderoShowphotoBundle:Judgement')->findAll();

        return array(
            'entities' => $entities,
        );
    }

    /**
     * Creates a new Judgement entity.
     *
     * @Route("/", name="judgement_create")
     * @Method("POST")
     * @Template("AuderoShowphotoBundle:Evaluation:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity = new Judgement();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('judgement_show', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Creates a form to create a Judgement entity.
     *
     * @param Judgement $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Judgement $entity)
    {
        $form = $this->createForm(new JudgementType(), $entity, array(
            'action' => $this->generateUrl('judgement_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new Judgement entity.
     *
     * @Route("/new", name="judgement_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $entity = new Judgement();
        $form   = $this->createCreateForm($entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Finds and displays a Judgement entity.
     *
     * @Route("/{id}", name="judgement_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AuderoShowphotoBundle:Judgement')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Judgement entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to edit an existing Judgement entity.
     *
     * @Route("/{id}/edit", name="judgement_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AuderoShowphotoBundle:Judgement')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Judgement entity.');
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
    * Creates a form to edit a Judgement entity.
    *
    * @param Judgement $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Judgement $entity)
    {
        $form = $this->createForm(new JudgementType(), $entity, array(
            'action' => $this->generateUrl('judgement_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing Judgement entity.
     *
     * @Route("/{id}", name="judgement_update")
     * @Method("PUT")
     * @Template("AuderoShowphotoBundle:Judgement:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AuderoShowphotoBundle:Judgement')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Judgement entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('judgement_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }
    /**
     * Deletes a Judgement entity.
     *
     * @Route("/{id}", name="judgement_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('AuderoShowphotoBundle:Judgement')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Judgement entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('judgement'));
    }

    /**
     * Creates a form to delete a Judgement entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('judgement_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }
}

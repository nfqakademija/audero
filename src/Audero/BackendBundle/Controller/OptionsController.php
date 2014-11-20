<?php

namespace Audero\BackendBundle\Controller;

use Audero\BackendBundle\Entity\Options;
use Audero\BackendBundle\Entity\OptionsRecord;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Constraints\DateTime;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\GetSetMethodNormalizer;

/**
 * Options controller.
 *
 * @Route("/admin/options")
 */
class OptionsController extends Controller
{

    /**
     * Displays options
     *
     * @Route("/", name="backend_options")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $options = $em->getRepository('AuderoBackendBundle:OptionsRecord')->findCurrent();

        if(!$options) {
            $options = new Options();
        }

        return array(
            'form_options' => $this->createForm('audero_backendbundle_options', $options)->createView()
        );
    }

    /**
     * Edits an existing Options entity.
     *
     * @Route("/", name="backend_options_update")
     * @Method("POST")
     */
    public function updateAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $encoders = array(new JsonEncoder());
        $normalizers = array(new GetSetMethodNormalizer());
        $serializer = new Serializer($normalizers, $encoders);

        $options = new Options();
        $form  = $this->createForm('audero_backendbundle_options', $options);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $record = new OptionsRecord();
            $jsonObj = $serializer->serialize($options, 'json');
            $record->setObject($jsonObj);
            $record->setDate(new \DateTime());
            $em->persist($record);
            $em->flush();

            return $this->redirect($this->generateUrl('backend_index'));
        }

        return $this->render('AuderoBackendBundle:Options:index.html.twig', array(
            'form_options' => $form->createView()
        ));
    }

}

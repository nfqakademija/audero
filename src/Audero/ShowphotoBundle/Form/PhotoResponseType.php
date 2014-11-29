<?php

namespace Audero\ShowphotoBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class PhotoResponseType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('photoUrl','url', array('required' => false))
            ->add('photoFile','file', array('required' => false))
            ->add('Upload', 'submit');
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Audero\ShowphotoBundle\Entity\PhotoResponse'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'audero_showphotobundle_photoresponse';
    }
}

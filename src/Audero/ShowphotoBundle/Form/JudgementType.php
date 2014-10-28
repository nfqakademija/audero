<?php

namespace Audero\ShowphotoBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class JudgementType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('decision')
            ->add('interpretation','entity', array('class'=>'Audero\ShowphotoBundle\Entity\Interpretation', 'property'=>'id'))
            ->add('user')
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Audero\ShowphotoBundle\Entity\Judgement'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'audero_showphotobundle_judgement';
    }
}

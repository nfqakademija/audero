<?php

namespace Audero\BackendBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Routing\Router;

class OptionsType extends AbstractType
{
    private $router;

    public function __construct(Router $router) {
        $this->router = $router;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('timeForResponse', 'integer')
            ->add('playersInOneRoom', 'integer')
            ->add('playerWishesCount', 'integer')
            ->add('save', 'submit')
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Audero\BackendBundle\Entity\Options'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'audero_backendbundle_options';
    }
}

<?php

namespace Audero\WebBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class WishListType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('wishes', 'collection', array('type' => new WishType(), 'allow_add'=> true,'allow_delete' => true));
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Audero\ShowphotoBundle\Entity\User',
        ));
    }

    public function getName()
    {
        return 'web_wish_list';
    }
}


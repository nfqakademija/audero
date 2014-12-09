<?php

namespace Audero\WebBundle\Form;

use Audero\ShowphotoBundle\Entity\Wish;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class WishType extends AbstractType
{
    private $wish;

    public function __construct(Wish $wish = null) {
        $this->wish = $wish;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('title', 'text');

        if(!is_null($this->wish)) {
            $builder->setData($this->wish);
        }
    }


    public function getName()
    {
        return 'showphoto_wish';
    }
}


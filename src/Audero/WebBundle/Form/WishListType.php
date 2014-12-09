<?php

namespace Audero\WebBundle\Form;

use Doctrine\Common\Collections\Collection;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class WishListType extends AbstractType
{
    private $wishes;

    public function __construct(Collection $wishes) {
        $this->wishes = $wishes;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {

    }

    public function getName()
    {
        return 'web_wish_list';
    }
}


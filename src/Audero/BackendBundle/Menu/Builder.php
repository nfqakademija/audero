<?php

namespace Audero\BackendBundle\Menu;

use Knp\Menu\FactoryInterface;
use Symfony\Component\DependencyInjection\ContainerAware;

class Builder extends ContainerAware
{
    public function mainMenu(FactoryInterface $factory, array $options)
    {
        $menu = $factory->createItem('root');

        $menu->addChild('labas', array('route' => 'audero_admin_index'));
        $menu->addChild('labas2', array('route' => 'audero_admin_index'));
        return $menu;
    }
}

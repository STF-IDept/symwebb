<?php

namespace Webb\ShipBundle\Menu;

use Knp\Menu\FactoryInterface;
use Symfony\Component\DependencyInjection\ContainerAware;

class Builder extends ContainerAware
{
    public function mainMenu(FactoryInterface $factory, array $options)
    {
        $menu = $factory->createItem('root');
        $menu->setChildrenAttribute('class', 'motd-links');

        $menu->addChild('Read Notes', array(
            'route' => 'webb_page_homepage'
        ));

        $menu->addChild('Post Note', array(
            'route' => 'webb_page_homepage'
        ));

        $menu->addChild('Captain\'s Log', array(
            'route' => 'webb_page_homepage'
        ));

        $menu->addChild('Ship\'s Specification', array(
            'route' => 'webb_page_homepage'
        ));

        $menu->addChild('STF Library', array(
            'uri' => 'http://www.star-fleet.com/library/'
        ));

        $menu->addChild('Handbook', array(
            'uri' => 'http://www.star-fleet.com/library/bookshelf/handbook/index.html'
        ));

        return $menu;
    }

}


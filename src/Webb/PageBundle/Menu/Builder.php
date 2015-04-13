<?php

namespace Webb\PageBundle\Menu;

use Knp\Menu\FactoryInterface;
use Symfony\Component\DependencyInjection\ContainerAware;

class Builder extends ContainerAware
{
    public function mainMenu(FactoryInterface $factory, array $options)
    {
        $menu = $factory->createItem('root');
        $menu->setChildrenAttribute('class', 'nav');

        $menu->addChild('Home Image', array(
            'route' => 'webb_page_homepage',
            'attributes' => array('class' => 'active'),
            'linkAttributes' => array('class' => 'brand'),
            'extras' => array('image' => 'bundles/webbpage/img/stf-button.png')));

        $menu->addChild('Home', array(
            'route' => 'webb_page_homepage'));

        $menu->addChild('Fleets', array(
            'uri' => '#',
            'attributes' => array('class' => 'dropdown'),
            'linkAttributes' => array('class' => 'dropdown-toggle', 'data-toggle' => 'dropdown'),
            'extras' => array('caret' => true),
            'childrenAttributes' => array('class' => 'dropdown-menu')));

        $query = $this->container->get('doctrine')->getManager()->createQueryBuilder()
            ->select('f')
            ->from('WebbShipBundle:Fleet', 'f')
            ->orderBy('f.shortname', 'ASC');

        $fleets = $query->getQuery()->getResult();

        foreach ($fleets as $fleet) {
            $menu['Fleets']->addChild($fleet->getName(), array(
                'route' => 'webb_ship_fleet_view',
                'routeParameters' => array('fleet' => $fleet->getShortName())
            ));
        }

        if($this->container->get('request')->get('fleet')) {
            $menu->addChild('Ships', array(
                'uri' => '#',
                'attributes' => array('class' => 'dropdown'),
                'linkAttributes' => array('class' => 'dropdown-toggle', 'data-toggle' => 'dropdown'),
                'extras' => array('caret' => true),
                'childrenAttributes' => array('class' => 'dropdown-menu')));

            $query = $this->container->get('doctrine')->getManager()->createQueryBuilder()
                ->select('s')
                ->from('WebbShipBundle:Ship', 's')
                ->orderBy('s.shortname', 'ASC');

            $ships = $query->getQuery()->getResult();

            foreach ($ships as $ship) {
                $menu['Ships']->addChild($ship->getName(), array(
                    'route' => 'webb_ship_ship_view',
                    'routeParameters' => array('fleet' => $this->container->get('request')->get('fleet'), 'ship'=> $ship->getShortName())
                ));
            }
        }

        if($this->container->get('request')->get('ship')) {
            $menu->addChild('Ship Admin', array(
                'uri' => '#',
                'attributes' => array('class' => 'dropdown'),
                'linkAttributes' => array('class' => 'dropdown-toggle', 'data-toggle' => 'dropdown'),
                'extras' => array('caret' => true),
                'childrenAttributes' => array('class' => 'dropdown-menu')));
        }

        return $menu;
    }

}
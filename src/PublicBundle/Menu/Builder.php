<?php

namespace PublicBundle\Menu;

use Knp\Menu\FactoryInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;

class Builder implements ContainerAwareInterface
{
    use ContainerAwareTrait;
    
    public function mainMenu(FactoryInterface $factory, array $options)
    {
        $menu = $factory->createItem('');

        $menu->addChild('first', array('route' => 'home'));

        $em = $this->container->get('doctrine')->getManager();

//        $menus = $em->getRepository('PublicBundle:Menus')->findMostRecent();

        $menu->addChild('second', array(
            'route' => 'fos_user_profile_show',
            'routeParameters' => array('id' => 1)
        ));

        // create another menu item
        $menu->addChild('third', array('route' => 'home'));
        // you can also add sub level's to your menu's as follows
        $menu['third']->addChild('third-first', array('route' => 'home'));

        // ... add more children

        return $menu;
    }

    public function sidebarMenu(FactoryInterface $factory, array $options)
    {
        $sidebar = $factory->createItem('sidebar');

        $em = $this->container->get('doctrine')->getManager();
        $menusEm = $em->getRepository('PublicBundle:Menus');
        $menusInfo = $menusEm->findBy(array('action' => 'sidebar', 'role' => 'ROLE_SUPER_ADMIN'));

        foreach ($menusInfo as $menu) {
            if ($menu->getPid() == 0) {
                $sidebar->addChild($menu->getId(), array(
                    'route' => $menu->getRoute(),
                    'label' => $menu->getTitle(),
                    'extras' => array(
                        'icon' => $menu->getIcon(),
                        'description' => $menu->getDescription(),
                    ),
                ));
            }else{
                $sidebar[$menu->getPid()]->addChild($menu->getId(), array(
                    'route' => $menu->getRoute(),
                    'label' => $menu->getTitle(),
                    'extras' => array(
                        'icon' => $menu->getIcon(),
                        'description' => $menu->getDescription(),
                    ),
                ));
            }
        }

        return $sidebar;
    }
}

<?php

namespace PublicBundle\Menu;

use Knp\Menu\FactoryInterface;

class MenuBuilder
{
    private $factory;
    private $doctrine;

    /**
     * @param FactoryInterface $factory
     *
     * Add any other dependency you need
     */
    public function __construct(FactoryInterface $factory, $doctrine)
    {
        $this->factory = $factory;
        $this->doctrine = $doctrine;
    }

    public function createMainMenu(array $options)
    {
        $menus = $this->factory->createItem('main');
        return $menus;
    }

    public function createSidebarMenu(array $options)
    {
        $sidebar = $this->factory->createItem('sidebar');
        $pMenusInfo = $this->setSidebarSql($options['role'], 0);
        foreach ($pMenusInfo as $pKey => $pMenu) {
            $this->addMenuChild($sidebar, $pKey, $pMenu)->setAttribute('class','nav-item');
            $menusInfo = $this->setSidebarSql($options['role'], $pMenu->getId());
            foreach ($menusInfo as $key => $menu) {
                $this->addMenuChild($sidebar[$pKey], $key, $menu);
            }
        }
        return $sidebar;
    }

    private function setSidebarSql($role, $pid)
    {
        $menu = $this->doctrine->getManager()->createQuery(
                "SELECT m FROM PublicBundle:Menus m WHERE m.action = 'sidebar' AND m.role = :role AND  m.pid = :pid ORDER BY m.sort ASC")
            ->setParameters(array('role' => $role, 'pid' => $pid))
            ->getResult();
        return $menu;
    }

    private function addMenuChild($menu, $key, $arr)
    {
        $menu->addChild($key, array(
            'route' => $arr->getRoute(),
            'label' => $arr->getTitle(),
            'extras' => array(
                'icon' => $arr->getIcon(),
                'description' => $arr->getDescription(),
            ),
        ));
        return $menu;
    }
}
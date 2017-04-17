<?php
/**
 * Created by PhpStorm.
 * User: Yue
 * Date: 2017/3/30
 * Time: 12:01
 */

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

    /**
     * Create SideBar Menus
     *
     * @param array $options
     * @return \Knp\Menu\ItemInterface $sidebar
     */
    public function createSidebarMenu(array $options)
    {
        krsort($options['role']);
        $pMenus = Array();
        foreach ($options['role'] as $role) {
            $roleMenus = $this->doctrine->getManager()->createQuery(
                "SELECT m FROM PublicBundle:Menus m WHERE m.action = 'sidebar' AND m.role = :role AND m.pid IS NULL ORDER BY m.sort ASC")
                ->setParameter('role', $role)
                ->getResult();
            $pMenus = array_merge($pMenus, $roleMenus);
        }

        $sidebar = $this->factory->createItem('sidebar');
        foreach ($pMenus as $pKey => $pMenu) {
            $this->addMenuChild($sidebar, $pKey, $pMenu)->setAttribute('class','nav-item');
            foreach ($pMenu->getChildren() as $key => $menu) {
                $this->addMenuChild($sidebar[$pKey], $key, $menu);
            }
        }
        return $sidebar;
    }

    /**
     * Add menu child
     *
     * @param mixed $menu
     * @param integer $key
     * @param mixed $child
     * @return mixed $menu
     */
    private function addMenuChild($menu, $key, $child)
    {
        $menu->addChild($key, array(
            'route' => $child->getRoute(),
            'label' => $child->getTitle(),
            'extras' => array(
                'icon' => $child->getIcon(),
                'description' => $child->getDescription(),
            ),
        ));
        return $menu;
    }
}
<?php
/**
 * Created by PhpStorm.
 * User: Yue
 * Date: 2017/3/30
 * Time: 12:01
 */

namespace PublicBundle\Menu;

use Doctrine\Bundle\DoctrineBundle\DoctrineBundle;
use Knp\Menu\FactoryInterface;

/**
 * Class MenuBuilder
 * @package PublicBundle\Menu
 */
class MenuBuilder
{
    private $factory;
    private $doctrine;

    /**
     * MenuBuilder constructor.
     *
     * @param FactoryInterface $factory
     * @param \Doctrine\Bundle\DoctrineBundle\Registry $doctrine
     */
    public function __construct(FactoryInterface $factory, $doctrine)
    {
        $this->factory = $factory;
        $this->doctrine = $doctrine;
    }

    /**
     * @param array $options
     * @return \Knp\Menu\ItemInterface
     */
    public function createMainMenu(array $options)
    {
        krsort($options["role"]);
        $pMenus = Array();
        foreach ($options["role"] as $role) {
            $roleMenus = $this->doctrine->getManager()->createQuery(
                "SELECT m FROM PublicBundle:Menus m WHERE m.action = 'sidebar' AND m.role = :role AND m.pid IS NULL ORDER BY m.sort ASC")
                ->setParameter("role", $role)
                ->getResult();
            $pMenus = array_merge($pMenus, $roleMenus);
        }

        $sidebar = $this->factory->createItem("sidebar");
        foreach ($pMenus as $pKey => $pMenu) {
            $this->addSubMenuToMenu($sidebar, $pKey, $pMenu);
            foreach ($pMenu->getChildren() as $key => $menu) {
                $this->addSubMenuToMenu($sidebar[$pKey], $key, $menu);
            }
        }
        return $sidebar;
    }

    /**
     * Create the side menu bar
     *
     * @param array $options
     * @return \Knp\Menu\ItemInterface $sidebar
     */
    public function createSidebarMenu(array $options)
    {
        krsort($options["role"]);
        $pMenus = Array();
        foreach ($options["role"] as $role) {
            $roleMenus = $this->doctrine->getManager()->createQuery(
                "SELECT m FROM PublicBundle:Menus m WHERE m.action = 'sidebar' AND m.role = :role AND m.pid IS NULL ORDER BY m.sort ASC")
                ->setParameter("role", $role)
                ->getResult();
            $pMenus = array_merge($pMenus, $roleMenus);
        }

        $sidebar = $this->factory->createItem("sidebar");
        foreach ($pMenus as $pKey => $pMenu) {
            $this->addSubMenuToMenu($sidebar, $pKey, $pMenu);
            foreach ($pMenu->getChildren() as $key => $menu) {
                $this->addSubMenuToMenu($sidebar[$pKey], $key, $menu);
                switch ($menu->getRoute()) {
                    case "displayModel":
                        $sidebar[$pKey][$key]->seturi("javascript:;");
                        break;
                    case "generalSettings":
                        $sidebar[$pKey][$key]->seturi("#general-settings-modal")->setLinkAttribute("data-toggle", "modal");
                        break;
                }

            }
        }
        return $sidebar;
    }

    /**
     * Add sub menu to the menu bar
     *
     * @param mixed $menu
     * @param integer $key
     * @param mixed $child
     * @return mixed $menu
     */
    private function addSubMenuToMenu($menu, $key, $child)
    {
        $menu->addChild($key, array(
            "route" => $child->getRoute(),
            "label" => $child->getTitle(),
            "extras" => array(
                "icon" => $child->getIcon(),
                "description" => $child->getDescription(),
            ),
        ));
        return $menu;
    }
}

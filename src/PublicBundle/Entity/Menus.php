<?php

namespace PublicBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Menus
 *
 * @ORM\Table(name="sy_menus")
 * @ORM\Entity(repositoryClass="PublicBundle\Entity\MenusRepository")
 */
class Menus
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="Menus", inversedBy="children")
     * @ORM\JoinColumn(name="pid", referencedColumnName ="id")
     */
    private $pid;

    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=255)
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="route", type="string", length=255)
     */
    private $route;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text", nullable=true)
     */
    private $description;

    /**
     * @var string
     *
     * @ORM\Column(name="role", type="string", length=255)
     */
    private $role;

    /**
     * @var string
     *
     * @ORM\Column(name="action", type="string", length=255)
     */
    private $action;

    /**
     * @var string
     *
     * @ORM\Column(name="icon", type="string", length=255)
     */
    private $icon;

    /**
     * @var integer
     *
     * @ORM\Column(name="sort", type="smallint")
     */
    private $sort;

    /**
     * @ORM\OneToMany(targetEntity="Menus", mappedBy="pid")
     */
    private  $children;


    /**
     * Menus constructor.
     */
    public  function  __construct()
    {
        $this->children = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set pid
     *
     * @param \PublicBundle\Entity\Menus $pid
     * @return Menus
     */
    public function setPid(\PublicBundle\Entity\Menus $pid = null)
    {
        $this->pid = $pid;

        return $this;
    }

    /**
     * Get pid
     *
     * @return \PublicBundle\Entity\Menus
     */
    public function getPid()
    {
        return $this->pid;
    }

    /**
     * Set title
     *
     * @param string $title
     * @return Menus
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set route
     *
     * @param string $route
     * @return Menus
     */
    public function setRoute($route)
    {
        $this->route = $route;

        return $this;
    }

    /**
     * Get route
     *
     * @return string
     */
    public function getRoute()
    {
        return $this->route;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return Menus
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set role
     *
     * @param string $role
     * @return Menus
     */
    public function setRole($role)
    {
        $this->role = $role;

        return $this;
    }

    /**
     * Get role
     *
     * @return string
     */
    public function getRole()
    {
        return $this->role;
    }

    /**
     * Set action
     *
     * @param string $action
     * @return Menus
     */
    public function setAction($action)
    {
        $this->action = $action;

        return $this;
    }

    /**
     * Get action
     *
     * @return string
     */
    public function getAction()
    {
        return $this->action;
    }

    /**
     * Set icon
     *
     * @param string $icon
     * @return Menus
     */
    public function setIcon($icon)
    {
        $this->icon = $icon;

        return $this;
    }

    /**
     * Get icon
     *
     * @return string
     */
    public function getIcon()
    {
        return $this->icon;
    }

    /**
     * Set sort
     *
     * @param integer $sort
     * @return Menus
     */
    public function setSort($sort)
    {
        $this->sort = $sort;

        return $this;
    }

    /**
     * Get sort
     *
     * @return integer
     */
    public function getSort()
    {
        return $this->sort;
    }

    /**
     * Add children
     *
     * @param \PublicBundle\Entity\Menus $children
     * @return Menus
     */
    public function addChild(\PublicBundle\Entity\Menus $children)
    {
        $this->children[] = $children;

        return $this;
    }

    /**
     * Remove children
     *
     * @param \PublicBundle\Entity\Menus $children
     */
    public function removeChild(\PublicBundle\Entity\Menus $children)
    {
        $this->children->removeElement($children);
    }

    /**
     * Get children
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getChildren()
    {
        return $this->children;
    }
}

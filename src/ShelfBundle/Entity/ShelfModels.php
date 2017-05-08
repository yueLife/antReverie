<?php

namespace ShelfBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ShelfModels
 *
 * @ORM\Table(name="sy_shelf_models")
 * @ORM\Entity(repositoryClass="ShelfBundle\Entity\ShelfModelsRepository")
 */
class ShelfModels
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
     * @ORM\ManyToOne(targetEntity="Shops")
     * @ORM\JoinColumn(name="shop_id", referencedColumnName="id")
     */
    private $shop;

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
     * @ORM\Column(name="style", type="text")
     */
    private $style;

    /**
     * @var string
     *
     * @ORM\Column(name="createtime", type="string", length=255)
     */
    private $createTime;

    /**
     * @var boolean
     *
     * @ORM\Column(name="del", type="boolean")
     */
    private $del = false;


    /**
     * ShelfModels constructor.
     */
    public function __construct()
    {
        $this->createTime = date('Y/m/d H:i:s', time());
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
     * Set shop
     *
     * @param \ShelfBundle\Entity\Shops $shop
     * @return ShelfModels
     */
    public function setShop(\ShelfBundle\Entity\Shops $shop = null)
    {
        $this->shop = $shop;

        return $this;
    }

    /**
     * Get shop
     *
     * @return \ShelfBundle\Entity\Shops
     */
    public function getShop()
    {
        return $this->shop;
    }

    /**
     * Set title
     *
     * @param string $title
     * @return ShelfModels
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
     * @return ShelfModels
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
     * Set style
     *
     * @param string $style
     * @return ShelfModels
     */
    public function setStyle($style)
    {
        $this->style = $style;

        return $this;
    }

    /**
     * Get style
     *
     * @return string
     */
    public function getStyle()
    {
        return $this->style;
    }

    /**
     * Set createTime
     *
     * @param string $createTime
     * @return ShelfModels
     */
    public function setCreateTime($createTime)
    {
        $this->createTime = $createTime;

        return $this;
    }

    /**
     * Get createTime
     *
     * @return string
     */
    public function getCreateTime()
    {
        return $this->createTime;
    }

    /**
     * Set del
     *
     * @param boolean $del
     * @return ShelfModels
     */
    public function setDel($del)
    {
        $this->del = $del;

        return $this;
    }

    /**
     * Get del
     *
     * @return boolean
     */
    public function getDel()
    {
        return $this->del;
    }
}

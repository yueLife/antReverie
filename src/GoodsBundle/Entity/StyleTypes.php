<?php

namespace GoodsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * StyleTypes
 *
 * @ORM\Table(name="sy_style_types")
 * @ORM\Entity(repositoryClass="GoodsBundle\Entity\StyleTypesRepository")
 */
class StyleTypes
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
     * @var integer
     *
     * @ORM\Column(name="shop_id", type="integer")
     */
    private $shopId;

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
     * StyleTypes constructor.
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
     * Set shopId
     *
     * @param integer $shopId
     * @return StyleTypes
     */
    public function setShopId($shopId)
    {
        $this->shopId = $shopId;

        return $this;
    }

    /**
     * Get shopId
     *
     * @return integer 
     */
    public function getShopId()
    {
        return $this->shopId;
    }

    /**
     * Set title
     *
     * @param string $title
     * @return StyleTypes
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
     * @return StyleTypes
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
     * @return StyleTypes
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
     * @return StyleTypes
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
     * @return StyleTypes
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

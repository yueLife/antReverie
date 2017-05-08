<?php

namespace ShelfBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Shops
 *
 * @ORM\Table(name="sy_shops")
 * @ORM\Entity(repositoryClass="ShelfBundle\Entity\ShopsRepository")
 */
class Shops
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
     * @var string
     *
     * @ORM\Column(name="shopname", type="string", length=255)
     */
    private $shopname;

    /**
     * @ORM\ManyToOne(targetEntity="Brands")
     * @ORM\JoinColumn(name="brand_id", referencedColumnName="id")
     */
    private $brand;

    /**
     * @ORM\ManyToOne(targetEntity="Plats")
     * @ORM\JoinColumn(name="plat_id", referencedColumnName="id")
     */
    private $plat;

    /**
     * @var string
     *
     * @ORM\Column(name="createtime", type="string", length=255)
     */
    private $createTime;

    /**
     * @var boolean
     *
     * @ORM\Column(name="active", type="boolean")
     */
    private $active = true;

    /**
     * @var boolean
     *
     * @ORM\Column(name="del", type="boolean")
     */
    private $del = false;

    /**
     * @ORM\ManyToMany(targetEntity="UsersBundle\Entity\Users")
     * @ORM\JoinTable(name="sy_users_shops")
     */
    private $users;

    /**
     * Shops constructor.
     */
    public function __construct()
    {
        $this->createTime = date('Y/m/d H:i:s', time());
        $this->users = new ArrayCollection();
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
     * Set shopname
     *
     * @param string $shopname
     * @return Shops
     */
    public function setShopname($shopname)
    {
        $this->shopname = $shopname;

        return $this;
    }

    /**
     * Get shopname
     *
     * @return string
     */
    public function getShopname()
    {
        return $this->shopname;
    }

    /**
     * Set brand
     *
     * @param \ShelfBundle\Entity\Brands $brand
     * @return Shops
     */
    public function setBrand(\ShelfBundle\Entity\Brands $brand = null)
    {
        $this->brand = $brand;

        return $this;
    }

    /**
     * Get brand
     *
     * @return \ShelfBundle\Entity\Brands
     */
    public function getBrand()
    {
        return $this->brand;
    }

    /**
     * Set plat
     *
     * @param \ShelfBundle\Entity\Plats $plat
     * @return Shops
     */
    public function setPlat(\ShelfBundle\Entity\Plats $plat = null)
    {
        $this->plat = $plat;

        return $this;
    }

    /**
     * Get plat
     *
     * @return \ShelfBundle\Entity\Plats
     */
    public function getPlat()
    {
        return $this->plat;
    }

    /**
     * Set createTime
     *
     * @param string $createTime
     * @return Shops
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
     * Set active
     *
     * @param boolean $active
     * @return Shops
     */
    public function setActive($active)
    {
        $this->active = $active;

        return $this;
    }

    /**
     * Get active
     *
     * @return boolean
     */
    public function getActive()
    {
        return $this->active;
    }

    /**
     * Set del
     *
     * @param boolean $del
     * @return Shops
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

    /**
     * Add users
     *
     * @param \UsersBundle\Entity\Users $users
     * @return Shops
     */
    public function addUser(\UsersBundle\Entity\Users $users)
    {
        $this->users[] = $users;

        return $this;
    }

    /**
     * Remove users
     *
     * @param \UsersBundle\Entity\Users $users
     */
    public function removeUser(\UsersBundle\Entity\Users $users)
    {
        $this->users->removeElement($users);
    }

    /**
     * Get users
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getUsers()
    {
        return $this->users;
    }
}

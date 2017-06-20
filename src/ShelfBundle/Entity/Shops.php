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
     * @ORM\Column(name="shopname", type="string", length=255, unique=true)
     */
    private $shopname;

    /**
     * @ORM\ManyToOne(targetEntity="ShelfBundle\Entity\Brands", inversedBy="shops")
     * @ORM\JoinColumn(name="brand_id", referencedColumnName="id")
     */
    private $brand;

    /**
     * @ORM\ManyToOne(targetEntity="ShelfBundle\Entity\Plats", inversedBy="shops")
     * @ORM\JoinColumn(name="plat_id", referencedColumnName="id")
     */
    private $plat;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="createtime", type="datetime")
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
     * @ORM\ManyToMany(targetEntity="UsersBundle\Entity\Users", inversedBy="shops")
     * @ORM\JoinTable(name="sy_users_shops")
     */
    private $users;

    /**
     * @ORM\OneToMany(targetEntity="ShelfBundle\Entity\ShelfModels", mappedBy="shop")
     */
    private $models;


    /**
     * Shops constructor.
     */
    public function __construct()
    {
        $this->createTime = new \DateTime;
        $this->users = new ArrayCollection();
        $this->models = new ArrayCollection();
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
     * Set createTime
     *
     * @param \DateTime $createTime
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
     * @return \DateTime 
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

    /**
     * Add models
     *
     * @param \ShelfBundle\Entity\ShelfModels $models
     * @return Shops
     */
    public function addModel(\ShelfBundle\Entity\ShelfModels $models)
    {
        $this->models[] = $models;

        return $this;
    }

    /**
     * Remove models
     *
     * @param \ShelfBundle\Entity\ShelfModels $models
     */
    public function removeModel(\ShelfBundle\Entity\ShelfModels $models)
    {
        $this->models->removeElement($models);
    }

    /**
     * Get models
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getModels()
    {
        return $this->models;
    }
}

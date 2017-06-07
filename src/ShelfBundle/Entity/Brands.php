<?php

namespace ShelfBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Brands
 *
 * @ORM\Table(name="sy_brands")
 * @ORM\Entity(repositoryClass="ShelfBundle\Entity\BrandsRepository")
 */
class Brands
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
     * @ORM\Column(name="brandname", type="string", length=255)
     */
    private $brandname;

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
     * @ORM\OneToMany(targetEntity="ShelfBundle\Entity\Shops", mappedBy="brand")
     */
    private $shops;

    /**
     * @ORM\OneToMany(targetEntity="ShelfBundle\Entity\ShelfUsers", mappedBy="brand")
     */
    private $shelfUsers;


    /**
     * Brands constructor.
     */
    public function __construct()
    {
        $this->createTime = new \DateTime;
        $this->shops = new ArrayCollection();
        $this->shelfUsers = new ArrayCollection();
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
     * Set brandname
     *
     * @param string $brandname
     * @return Brands
     */
    public function setBrandname($brandname)
    {
        $this->brandname = $brandname;

        return $this;
    }

    /**
     * Get brandname
     *
     * @return string 
     */
    public function getBrandname()
    {
        return $this->brandname;
    }

    /**
     * Set createTime
     *
     * @param \DateTime $createTime
     * @return Brands
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
     * @return Brands
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
     * @return Brands
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
     * Add shops
     *
     * @param \ShelfBundle\Entity\Shops $shops
     * @return Brands
     */
    public function addShop(\ShelfBundle\Entity\Shops $shops)
    {
        $this->shops[] = $shops;

        return $this;
    }

    /**
     * Remove shops
     *
     * @param \ShelfBundle\Entity\Shops $shops
     */
    public function removeShop(\ShelfBundle\Entity\Shops $shops)
    {
        $this->shops->removeElement($shops);
    }

    /**
     * Get shops
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getShops()
    {
        return $this->shops;
    }

    /**
     * Add shelfUsers
     *
     * @param \ShelfBundle\Entity\ShelfUsers $shelfUsers
     * @return Brands
     */
    public function addShelfUser(\ShelfBundle\Entity\ShelfUsers $shelfUsers)
    {
        $this->shelfUsers[] = $shelfUsers;

        return $this;
    }

    /**
     * Remove shelfUsers
     *
     * @param \ShelfBundle\Entity\ShelfUsers $shelfUsers
     */
    public function removeShelfUser(\ShelfBundle\Entity\ShelfUsers $shelfUsers)
    {
        $this->shelfUsers->removeElement($shelfUsers);
    }

    /**
     * Get shelfUsers
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getShelfUsers()
    {
        return $this->shelfUsers;
    }
}

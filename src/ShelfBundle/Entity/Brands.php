<?php

namespace ShelfBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

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
     * Brands constructor.
     */
    public function __construct()
    {
        $this->createTime = new \DateTime;
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
}

<?php

namespace ShelfBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use UsersBundle\Entity\Users;

/**
 * ShelfUsers
 *
 * @ORM\Table(name="sy_shelf_users")
 * @ORM\Entity(repositoryClass="ShelfBundle\Entity\ShelfUsersRepository")
 */
class ShelfUsers
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
     * @ORM\OneToOne(targetEntity="UsersBundle\Entity\Users", inversedBy="shelfUser")
     * @ORM\JoinColumn(name="uid", referencedColumnName="id")
     */
    private $user;

    /**
     * @var string
     *
     * @ORM\Column(name="personal", type="text")
     */
    private $personal;

    /**
     * @var string
     *
     * @ORM\Column(name="img_list", type="text")
     */
    private $imgList;

    /**
     * @ORM\ManyToOne(targetEntity="ShelfBundle\Entity\Plats", inversedBy="shelfUsers")
     * @ORM\JoinColumn(name="plat_id", referencedColumnName="id")
     */
    private $plat;

    /**
     * @ORM\ManyToOne(targetEntity="ShelfBundle\Entity\Brands", inversedBy="shelfUsers")
     * @ORM\JoinColumn(name="brand_id", referencedColumnName="id")
     */
    private $brand;


    /**
     * ShelfUsers constructor.
     * @param Users $user
     */
    public function __construct(Users $user)
    {
        $this->user = $user;
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
     * Set personal
     *
     * @param string $personal
     * @return ShelfUsers
     */
    public function setPersonal($personal)
    {
        $this->personal = $personal;

        return $this;
    }

    /**
     * Get personal
     *
     * @return string 
     */
    public function getPersonal()
    {
        return $this->personal;
    }

    /**
     * Set imgList
     *
     * @param string $imgList
     * @return ShelfUsers
     */
    public function setImgList($imgList)
    {
        $this->imgList = $imgList;

        return $this;
    }

    /**
     * Get imgList
     *
     * @return string 
     */
    public function getImgList()
    {
        return $this->imgList;
    }

    /**
     * Set user
     *
     * @param \UsersBundle\Entity\Users $user
     * @return ShelfUsers
     */
    public function setUser(\UsersBundle\Entity\Users $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \UsersBundle\Entity\Users 
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set plat
     *
     * @param \ShelfBundle\Entity\Plats $plat
     * @return ShelfUsers
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
     * Set brand
     *
     * @param \ShelfBundle\Entity\Brands $brand
     * @return ShelfUsers
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
}

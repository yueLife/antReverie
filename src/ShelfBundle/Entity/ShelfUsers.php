<?php

namespace ShelfBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use UsersBundle\Entity\Users;

/**
 * ShelfUsers
 *
 * @ORM\Table(name="sy_shelf_users")
 * @ORM\Entity(repositoryClass="ShelfBundle\Entity\UserShelvesRepository")
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
     * @ORM\OneToOne(targetEntity="UsersBundle\Entity\Users")
     * @ORM\JoinColumn(name="uid", referencedColumnName="id")
     */
    private $user;

    /**
     * @var string
     *
     * @ORM\Column(name="shelf_set", type="text")
     */
    private $shelfSet;

    /**
     * @var string
     *
     * @ORM\Column(name="img_list", type="text")
     */
    private $imgList;

    /**
     * @var integer
     *
     * @ORM\Column(name="plat_now", type="integer")
     */
    private $platNow;

    /**
     * @var integer
     *
     * @ORM\Column(name="brand_now", type="integer")
     */
    private $brandNow;


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
     * Set user
     *
     * @param \UserBundle\Entity\Users $user
     * @return ShelfUsers
     */
    public function setUser(\UserBundle\Entity\Users $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \UserBundle\Entity\Users
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set shelfSet
     *
     * @param string $shelfSet
     * @return ShelfUsers
     */
    public function setShelfSet($shelfSet)
    {
        $this->shelfSet = $shelfSet;

        return $this;
    }

    /**
     * Get shelfSet
     *
     * @return string
     */
    public function getShelfSet()
    {
        return $this->shelfSet;
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
     * Set platNow
     *
     * @param integer $platNow
     * @return ShelfUsers
     */
    public function setPlatNow($platNow)
    {
        $this->platNow = $platNow;

        return $this;
    }

    /**
     * Get platNow
     *
     * @return integer
     */
    public function getPlatNow()
    {
        return $this->platNow;
    }

    /**
     * Set brandNow
     *
     * @param integer $brandNow
     * @return ShelfUsers
     */
    public function setBrandNow($brandNow)
    {
        $this->brandNow = $brandNow;

        return $this;
    }

    /**
     * Get brandNow
     *
     * @return integer
     */
    public function getBrandNow()
    {
        return $this->brandNow;
    }
}

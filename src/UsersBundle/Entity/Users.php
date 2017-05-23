<?php

namespace UsersBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use FOS\UserBundle\Entity\User as BaseUser;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Users
 *
 * @ORM\Table(name="sy_users")
 * @ORM\Entity(repositoryClass="UsersBundle\Entity\UsersRepository")
 */
class Users extends BaseUser
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(name="avatar", type="string", options={"default":"avatar.png"})
     */
    protected $avatar = "avatar.png";

    /**
     * @ORM\OneToMany(targetEntity="PublicBundle\Entity\UploadFiles", mappedBy="user")
     */
    private $files;

    /**
     * @ORM\ManyToMany(targetEntity="ShelfBundle\Entity\Shops", mappedBy="users")
     */
    protected $shops;

    /**
     * @ORM\OneToOne(targetEntity="ShelfBundle\Entity\ShelfUsers", mappedBy="user")
     */
    private $shelfUser;


    /**
     * Users constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->files = new ArrayCollection();
        $this->shops = new ArrayCollection();
    }

    /**
     * Set avatar
     *
     * @param string $avatar
     * @return Users
     */
    public function setAvatar($avatar)
    {
        $this->avatar = $avatar;

        return $this;
    }

    /**
     * Get avatar
     *
     * @return string
     */
    public function getAvatar()
    {
        return $this->avatar;
    }

    /**
     * Add files
     *
     * @param \PublicBundle\Entity\UploadFiles $files
     * @return Users
     */
    public function addFile(\PublicBundle\Entity\UploadFiles $files)
    {
        $this->files[] = $files;

        return $this;
    }

    /**
     * Remove files
     *
     * @param \PublicBundle\Entity\UploadFiles $files
     */
    public function removeFile(\PublicBundle\Entity\UploadFiles $files)
    {
        $this->files->removeElement($files);
    }

    /**
     * Get files
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getFiles()
    {
        return $this->files;
    }

    /**
     * Add shops
     *
     * @param \ShelfBundle\Entity\Shops $shops
     * @return Users
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
     * Set shelfUser
     *
     * @param \ShelfBundle\Entity\ShelfUsers $shelfUser
     * @return Users
     */
    public function setShelfUser(\ShelfBundle\Entity\ShelfUsers $shelfUser = null)
    {
        $this->shelfUser = $shelfUser;

        return $this;
    }

    /**
     * Get shelfUser
     *
     * @return \ShelfBundle\Entity\ShelfUsers 
     */
    public function getShelfUser()
    {
        return $this->shelfUser;
    }
}

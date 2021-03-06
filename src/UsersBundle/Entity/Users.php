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
     * @var \DateTime
     *
     * @ORM\Column(name="createtime", type="datetime")
     */
    protected $createTime;

    /**
     * @ORM\OneToMany(targetEntity="PublicBundle\Entity\UploadFiles", mappedBy="user")
     */
    protected $files;

    /**
     * @ORM\ManyToMany(targetEntity="ShelfBundle\Entity\Shops", mappedBy="users")
     */
    protected $shops;

    /**
     * @ORM\OneToOne(targetEntity="ShelfBundle\Entity\ShelfUsers", mappedBy="user")
     */
    protected $shelfUser;

    /**
     * @ORM\OneToMany(targetEntity="WordsBundle\Entity\Words", mappedBy="adder")
     */
    protected $addWord;

    /**
     * @ORM\OneToMany(targetEntity="WordsBundle\Entity\Words", mappedBy="deleter")
     */
    protected $delWord;


    /**
     * Users constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->createTime = new \DateTime;
        $this->lastLogin = new \DateTime;
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
     * Set createTime
     *
     * @param \DateTime $createTime
     * @return Users
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

    /**
     * Add addWord
     *
     * @param \WordsBundle\Entity\Words $addWord
     * @return Users
     */
    public function addAddWord(\WordsBundle\Entity\Words $addWord)
    {
        $this->addWord[] = $addWord;

        return $this;
    }

    /**
     * Remove addWord
     *
     * @param \WordsBundle\Entity\Words $addWord
     */
    public function removeAddWord(\WordsBundle\Entity\Words $addWord)
    {
        $this->addWord->removeElement($addWord);
    }

    /**
     * Get addWord
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getAddWord()
    {
        return $this->addWord;
    }

    /**
     * Add delWord
     *
     * @param \WordsBundle\Entity\Words $delWord
     * @return Users
     */
    public function addDelWord(\WordsBundle\Entity\Words $delWord)
    {
        $this->delWord[] = $delWord;

        return $this;
    }

    /**
     * Remove delWord
     *
     * @param \WordsBundle\Entity\Words $delWord
     */
    public function removeDelWord(\WordsBundle\Entity\Words $delWord)
    {
        $this->delWord->removeElement($delWord);
    }

    /**
     * Get delWord
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getDelWord()
    {
        return $this->delWord;
    }
}

<?php

namespace PublicBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use UsersBundle\Entity\Users;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * UploadFiles
 *
 * @ORM\Table(name="sy_upload_files")
 * @ORM\Entity(repositoryClass="PublicBundle\Entity\UploadFilesRepository")
 */
class UploadFiles
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
     * @var integer
     *
     * @ORM\ManyToOne(targetEntity="UsersBundle\Entity\Users", inversedBy="files")
     * @ORM\JoinColumn(name="uid", referencedColumnName="id")
     */
    protected $user;

    /**
     * @var string
     *
     * @ORM\Column(name="filename", type="string", length=255)
     */
    protected $filename;

    /**
     * @var string
     *
     * @ORM\Column(name="oldname", type="string", length=255)
     */
    protected $oldname;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="uploadTime", type="datetime")
     */
    protected $uploadTime;

    /**
     * @var string
     *
     * @ORM\Column(name="state", type="string", length=255)
     */
    protected $state = 'unread';

    /**
     * @var string
     *
     * @ORM\Column(name="type", type="string", length=255)
     */
    protected $fileType = null;

    /**
     * @var boolean
     *
     * @ORM\Column(name="del", type="boolean")
     */
    protected $del = false;

    /**
     * @ORM\OneToMany(targetEntity="ShelfBundle\Entity\ShelfGoods", mappedBy="file")
     */
    protected $goods;


    /**
     * UploadFiles constructor.
     * @param Users $user
     */
    public function __construct(Users $user)
    {
        $this->uploadTime = new \DateTime;
        $this->user = $user;
        $this->goods = new ArrayCollection();
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
     * @param \UsersBundle\Entity\Users $user
     * @return UploadFiles
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
     * Set filename
     *
     * @param string $filename
     * @return UploadFiles
     */
    public function setFilename($filename)
    {
        $this->filename = $filename;

        return $this;
    }

    /**
     * Get filename
     *
     * @return string
     */
    public function getFilename()
    {
        return $this->filename;
    }

    /**
     * Set oldname
     *
     * @param string $oldname
     * @return UploadFiles
     */
    public function setOldname($oldname)
    {
        $this->oldname = $oldname;

        return $this;
    }

    /**
     * Get oldname
     *
     * @return string
     */
    public function getOldname()
    {
        return $this->oldname;
    }

    /**
     * Set uploadTime
     *
     * @param \DateTime $uploadTime
     * @return UploadFiles
     */
    public function setUploadTime($uploadTime)
    {
        $this->uploadTime = $uploadTime;

        return $this;
    }

    /**
     * Get uploadTime
     *
     * @return \DateTime
     */
    public function getUploadTime()
    {
        return $this->uploadTime;
    }

    /**
     * Set state
     *
     * @param string $state
     * @return UploadFiles
     */
    public function setState($state)
    {
        $this->state = $state;

        return $this;
    }

    /**
     * Get state
     *
     * @return string
     */
    public function getState()
    {
        return $this->state;
    }

    /**
     * Set fileType
     *
     * @param string $fileType
     * @return UploadFiles
     */
    public function setFileType($fileType)
    {
        $this->fileType = $fileType;

        return $this;
    }

    /**
     * Get fileType
     *
     * @return string
     */
    public function getFileType()
    {
        return $this->fileType;
    }

    /**
     * Set del
     *
     * @param boolean $del
     * @return UploadFiles
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
     * Add goods
     *
     * @param \ShelfBundle\Entity\ShelfGoods $goods
     * @return UploadFiles
     */
    public function addGood(\ShelfBundle\Entity\ShelfGoods $goods)
    {
        $this->goods[] = $goods;

        return $this;
    }

    /**
     * Remove goods
     *
     * @param \ShelfBundle\Entity\ShelfGoods $goods
     */
    public function removeGood(\ShelfBundle\Entity\ShelfGoods $goods)
    {
        $this->goods->removeElement($goods);
    }

    /**
     * Get goods
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getGoods()
    {
        return $this->goods;
    }
}

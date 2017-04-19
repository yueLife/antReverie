<?php

namespace PublicBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use UsersBundle\Entity\Users;

/**
 * FilesEntity
 */
class FilesEntity
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
     * @ORM\ManyToOne(targetEntity="UsersBundle\Entity\Users")
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
     * @var string
     *
     * @ORM\Column(name="uploadTime", type="string", length=255)
     */
    protected $uploadTime;

    /**
     * @var boolean
     *
     * @ORM\Column(name="del", type="boolean")
     */
    protected $del = false;


    /**
     * FilesEntity constructor.
     * @param Users $user
     */
    public function __construct(Users $user)
    {
        $this->uploadTime = date('Y/m/d H:i:s', time());
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
     * @param \UsersBundle\Entity\Users $user
     * @return FilesEntity
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
     * @return FilesEntity
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
     * @return FilesEntity
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
     * @param string $uploadTime
     * @return FilesEntity
     */
    public function setUploadTime($uploadTime)
    {
        $this->uploadTime = $uploadTime;

        return $this;
    }

    /**
     * Get uploadTime
     *
     * @return string
     */
    public function getUploadTime()
    {
        return $this->uploadTime;
    }

    /**
     * Set del
     *
     * @param boolean $del
     * @return FilesEntity
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

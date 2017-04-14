<?php

namespace GoodsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use UsersBundle\Entity\Users;

/**
 * GoodsFiles
 *
 * @ORM\Table(name="sy_goods_files")
 * @ORM\Entity(repositoryClass="GoodsBundle\Entity\GoodsFilesRepository")
 */
class GoodsFiles
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
     * @ManyToOne(targetEntity="UsersBundle\Entity\Users")
     * @JoinColumn(name="uid", referencedColumnName="id")
     */
    private $user;

    /**
     * @var string
     *
     * @ORM\Column(name="filename", type="string", length=255)
     */
    private $filename;

    /**
     * @var string
     *
     * @ORM\Column(name="oldname", type="string", length=255)
     */
    private $oldname;

    /**
     * @var string
     *
     * @ORM\Column(name="uploadtime", type="string", length=255)
     */
    private $uploadTime;

    /**
     * @var boolean
     *
     * @ORM\Column(name="del", type="boolean")
     */
    private $del = false;

    /**
     * @var string
     *
     * @ORM\Column(name="state", type="string", length=255)
     */
    private $state = 'unread';

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
     * @return GoodsFiles
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
     * @return GoodsFiles
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
     * @return GoodsFiles
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
     * @return GoodsFiles
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
     * @return GoodsFiles
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
     * Set state
     *
     * @param string $state
     * @return GoodsFiles
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
}

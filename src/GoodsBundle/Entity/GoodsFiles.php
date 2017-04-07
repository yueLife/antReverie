<?php

namespace GoodsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

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
     * @ORM\Column(name="uid", type="integer")
     */
    private $uid;

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
     * @ORM\Column(name="is_del", type="boolean")
     */
    private $isDel = false;

    /**
     * @var string
     *
     * @ORM\Column(name="state", type="string", length=255)
     */
    private $state = 'unread';

    public function __construct()
    {
        $this->uploadTime = $this->uploadTime = date('Y/m/d H:i:s', time());
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
     * Set uid
     *
     * @param integer $uid
     * @return GoodsFiles
     */
    public function setUid($uid)
    {
        $this->uid = $uid;

        return $this;
    }

    /**
     * Get uid
     *
     * @return integer 
     */
    public function getUid()
    {
        return $this->uid;
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
     * Set isDel
     *
     * @param boolean $isDel
     * @return GoodsFiles
     */
    public function setIsDel($isDel)
    {
        $this->isDel = $isDel;

        return $this;
    }

    /**
     * Get isDel
     *
     * @return boolean 
     */
    public function getIsDel()
    {
        return $this->isDel;
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

<?php

namespace PublicBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

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
     * @ORM\Column(name="del", type="boolean")
     */
    private $del = false;


    public function __construct()
    {
        $this->uploadTime = date('Y/m/d H:i:s', time());
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
     * @return UploadFiles
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
     * @param string $uploadTime
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
}

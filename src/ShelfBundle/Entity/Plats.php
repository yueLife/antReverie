<?php

namespace ShelfBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Plats
 *
 * @ORM\Table(name="sy_plats")
 * @ORM\Entity(repositoryClass="ShelfBundle\Entity\PlatsRepository")
 */
class Plats
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
     * @ORM\Column(name="platname", type="string", length=255)
     */
    private $platname;

    /**
     * @var string
     *
     * @ORM\Column(name="url", type="text")
     */
    private $url;

    /**
     * @var string
     *
     * @ORM\Column(name="createtime", type="string", length=255)
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
     * Plats constructor.
     */
    public function __construct()
    {
        $this->createTime = date('Y/m/d H:i:s', time());
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
     * Set platname
     *
     * @param string $platname
     * @return Plats
     */
    public function setPlatname($platname)
    {
        $this->platname = $platname;

        return $this;
    }

    /**
     * Get platname
     *
     * @return string 
     */
    public function getPlatname()
    {
        return $this->platname;
    }

    /**
     * Set url
     *
     * @param string $url
     * @return Plats
     */
    public function setUrl($url)
    {
        $this->url = $url;

        return $this;
    }

    /**
     * Get url
     *
     * @return string 
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * Set createTime
     *
     * @param string $createTime
     * @return Plats
     */
    public function setCreateTime($createTime)
    {
        $this->createTime = $createTime;

        return $this;
    }

    /**
     * Get createTime
     *
     * @return string 
     */
    public function getCreateTime()
    {
        return $this->createTime;
    }

    /**
     * Set active
     *
     * @param boolean $active
     * @return Plats
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
     * @return Plats
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

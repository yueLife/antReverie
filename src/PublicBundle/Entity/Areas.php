<?php

namespace PublicBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Areas
 *
 * @ORM\Table(name="sy_areas")
 * @ORM\Entity(repositoryClass="PublicBundle\Entity\AreasRepository")
 */
class Areas
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
     * @ORM\Column(name="pid", type="integer")
     */
    private $pid;

    /**
     * @var string
     *
     * @ORM\Column(name="area_name", type="string")
     */
    private $areaname;

    /**
     * @var string
     *
     * @ORM\Column(name="area_spell", type="string", nullable=true)
     */
    private $areaSpell;

    /**
     * @var integer
     *
     * @ORM\Column(name="area_type", type="integer")
     */
    private $areaType;


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
     * Set pid
     *
     * @param integer $pid
     * @return Areas
     */
    public function setPid($pid)
    {
        $this->pid = $pid;

        return $this;
    }

    /**
     * Get pid
     *
     * @return integer
     */
    public function getPid()
    {
        return $this->pid;
    }

    /**
     * Set areaname
     *
     * @param string $areaname
     * @return Areas
     */
    public function setAreaname($areaname)
    {
        $this->areaname = $areaname;

        return $this;
    }

    /**
     * Get areaname
     *
     * @return string
     */
    public function getAreaname()
    {
        return $this->areaname;
    }

    /**
     * Set areaSpell
     *
     * @param string $areaSpell
     * @return Areas
     */
    public function setAreaSpell($areaSpell)
    {
        $this->areaSpell = $areaSpell;

        return $this;
    }

    /**
     * Get areaSpell
     *
     * @return string
     */
    public function getAreaSpell()
    {
        return $this->areaSpell;
    }

    /**
     * Set areaType
     *
     * @param integer $areaType
     * @return Areas
     */
    public function setAreaType($areaType)
    {
        $this->areaType = $areaType;

        return $this;
    }

    /**
     * Get areaType
     *
     * @return integer
     */
    public function getAreaType()
    {
        return $this->areaType;
    }
}

<?php

namespace MichelinBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;


/**
 * MichelinCities
 *
 * @ORM\Table(name="sy_michelin_cities")
 * @ORM\Entity(repositoryClass="MichelinBundle\Entity\MichelinCitiesRepository")
 */
class MichelinCities
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
     * @ORM\Column(name="pid", type="integer", nullable=true)
     */
    private $pid;

    /**
     * @var integer
     *
     * @ORM\Column(name="city_list", type="integer")
     */
    private $cityList;

    /**
     * @var integer
     *
     * @ORM\Column(name="area_id", type="integer")
     */
    private $areaId;

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
     * @return MichelinCities
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
     * Set cityList
     *
     * @param integer $cityList
     * @return MichelinCities
     */
    public function setCityList($cityList)
    {
        $this->cityList = $cityList;

        return $this;
    }

    /**
     * Get cityList
     *
     * @return integer
     */
    public function getCityList()
    {
        return $this->cityList;
    }

    /**
     * Set areaId
     *
     * @param integer $areaId
     * @return MichelinCities
     */
    public function setAreaId($areaId)
    {
        $this->areaId = $areaId;

        return $this;
    }

    /**
     * Get areaId
     *
     * @return integer
     */
    public function getAreaId()
    {
        return $this->areaId;
    }

    /**
     * Set areaname
     *
     * @param string $areaname
     * @return MichelinCities
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
     * @return MichelinCities
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
}

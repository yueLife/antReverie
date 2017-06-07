<?php

namespace MichelinBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * MichelinStores
 *
 * @ORM\Table(name="sy_michelin_stores")
 * @ORM\Entity(repositoryClass="MichelinBundle\Entity\MichelinStoresRepository")
 */
class MichelinStores
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
     * @ORM\Column(name="cid", type="integer")
     */
    private $cid;

    /**
     * @var integer
     *
     * @ORM\Column(name="number", type="integer")
     */
    private $number;

    /**
     * @var string
     *
     * @ORM\Column(name="store_name", type="string")
     */
    private $storename;

    /**
     * @var string
     *
     * @ORM\Column(name="address", type="string")
     */
    private $address;


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
     * Set cid
     *
     * @param integer $cid
     * @return MichelinStores
     */
    public function setCid($cid)
    {
        $this->cid = $cid;

        return $this;
    }

    /**
     * Get cid
     *
     * @return integer
     */
    public function getCid()
    {
        return $this->cid;
    }

    /**
     * Set number
     *
     * @param integer $number
     * @return MichelinStores
     */
    public function setNumber($number)
    {
        $this->number = $number;

        return $this;
    }

    /**
     * Get number
     *
     * @return integer
     */
    public function getNumber()
    {
        return $this->number;
    }

    /**
     * Set storename
     *
     * @param string $storename
     * @return MichelinStores
     */
    public function setStorename($storename)
    {
        $this->storename = $storename;

        return $this;
    }

    /**
     * Get storename
     *
     * @return string
     */
    public function getStorename()
    {
        return $this->storename;
    }

    /**
     * Set address
     *
     * @param string $address
     * @return MichelinStores
     */
    public function setAddress($address)
    {
        $this->address = $address;

        return $this;
    }

    /**
     * Get address
     *
     * @return string
     */
    public function getAddress()
    {
        return $this->address;
    }
}

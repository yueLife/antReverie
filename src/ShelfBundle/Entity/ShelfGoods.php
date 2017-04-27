<?php

namespace ShelfBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use PublicBundle\Entity\UploadFiles;

/**
 * ShelfGoods
 *
 * @ORM\Table(name="sy_shelf_goods")
 * @ORM\Entity(repositoryClass="ShelfBundle\Entity\ShelfGoodsRepository")
 */
class ShelfGoods
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
     * @ORM\ManyToOne(targetEntity="PublicBundle\Entity\UploadFiles")
     * @ORM\JoinColumn(name="fid", referencedColumnName="id")
     */
    private $file;

    /**
     * @var string
     *
     * @ORM\Column(name="goodsname", type="string", length=255, nullable=true)
     */
    private $goodsname;

    /**
     * @var string
     *
     * @ORM\Column(name="goods_bn", type="string", length=255, nullable=true)
     */
    private $goodsBn;

    /**
     * @var integer
     *
     * @ORM\Column(name="goods_id", type="integer")
     */
    private $goodsId;

    /**
     * @var string
     *
     * @ORM\Column(name="introduce", type="string", length=255, nullable=true)
     */
    private $introduce;

    /**
     * @var string
     *
     * @ORM\Column(name="detail_introduce", type="string", length=255, nullable=true)
     */
    private $detailIntroduce;

    /**
     * @var string
     *
     * @ORM\Column(name="img_url", type="string", length=255)
     */
    private $imgUrl;

    /**
     * @var float
     *
     * @ORM\Column(name="tag_price", type="float", length=11, scale=2, nullable=true)
     */
    private $tagPrice;

    /**
     * @var float
     *
     * @ORM\Column(name="act_price", type="float", length=11, scale=2, nullable=true)
     */
    private $actPrice;

    /**
     * @var float
     *
     * @ORM\Column(name="cou_price", type="float", length=11, scale=2, nullable=true)
     */
    private $couPrice;

    /**
     * @var string
     *
     * @ORM\Column(name="unit", type="string", length=255, nullable=true)
     */
    private $unit;


    /**
     * ShelfGoods constructor.
     * @param UploadFiles $file
     */
    public function __construct(UploadFiles $file)
    {
        $this->file = $file;
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
     * Set file
     *
     * @param \PublicBundle\Entity\UploadFiles $file
     * @return ShelfGoods
     */
    public function setFile(\PublicBundle\Entity\UploadFiles $file = null)
    {
        $this->file = $file;

        return $this;
    }

    /**
     * Get file
     *
     * @return \PublicBundle\Entity\UploadFiles
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * Set goodsname
     *
     * @param string $goodsname
     * @return ShelfGoods
     */
    public function setGoodsname($goodsname)
    {
        $this->goodsname = $goodsname;

        return $this;
    }

    /**
     * Get goodsname
     *
     * @return string
     */
    public function getGoodsname()
    {
        return $this->goodsname;
    }

    /**
     * Set goodsBn
     *
     * @param string $goodsBn
     * @return ShelfGoods
     */
    public function setGoodsBn($goodsBn)
    {
        $this->goodsBn = $goodsBn;

        return $this;
    }

    /**
     * Get goodsBn
     *
     * @return string
     */
    public function getGoodsBn()
    {
        return $this->goodsBn;
    }

    /**
     * Set goodsId
     *
     * @param string $goodsId
     * @return ShelfGoods
     */
    public function setGoodsId($goodsId)
    {
        $this->goodsId = $goodsId;

        return $this;
    }

    /**
     * Get goodsId
     *
     * @return string
     */
    public function getGoodsId()
    {
        return $this->goodsId;
    }

    /**
     * Set introduce
     *
     * @param string $introduce
     * @return ShelfGoods
     */
    public function setIntroduce($introduce)
    {
        $this->introduce = $introduce;

        return $this;
    }

    /**
     * Get introduce
     *
     * @return string
     */
    public function getIntroduce()
    {
        return $this->introduce;
    }

    /**
     * Set detailIntroduce
     *
     * @param string $detailIntroduce
     * @return ShelfGoods
     */
    public function setDetailIntroduce($detailIntroduce)
    {
        $this->detailIntroduce = $detailIntroduce;

        return $this;
    }

    /**
     * Get detailIntroduce
     *
     * @return string
     */
    public function getDetailIntroduce()
    {
        return $this->detailIntroduce;
    }

    /**
     * Set imgUrl
     *
     * @param string $imgUrl
     * @return ShelfGoods
     */
    public function setImgUrl($imgUrl)
    {
        $this->imgUrl = $imgUrl;

        return $this;
    }

    /**
     * Get imgUrl
     *
     * @return string
     */
    public function getImgUrl()
    {
        return $this->imgUrl;
    }

    /**
     * Set tagPrice
     *
     * @param float $tagPrice
     * @return ShelfGoods
     */
    public function setTagPrice($tagPrice)
    {
        $this->tagPrice = $tagPrice;

        return $this;
    }

    /**
     * Get tagPrice
     *
     * @return float
     */
    public function getTagPrice()
    {
        return $this->tagPrice;
    }

    /**
     * Set actPrice
     *
     * @param float $actPrice
     * @return ShelfGoods
     */
    public function setActPrice($actPrice)
    {
        $this->actPrice = $actPrice;

        return $this;
    }

    /**
     * Get actPrice
     *
     * @return float
     */
    public function getActPrice()
    {
        return $this->actPrice;
    }

    /**
     * Set couPrice
     *
     * @param float $couPrice
     * @return ShelfGoods
     */
    public function setCouPrice($couPrice)
    {
        $this->couPrice = $couPrice;

        return $this;
    }

    /**
     * Get couPrice
     *
     * @return float
     */
    public function getCouPrice()
    {
        return $this->couPrice;
    }

    /**
     * Set unit
     *
     * @param string $unit
     * @return ShelfGoods
     */
    public function setUnit($unit)
    {
        $this->unit = $unit;

        return $this;
    }

    /**
     * Get unit
     *
     * @return string
     */
    public function getUnit()
    {
        return $this->unit;
    }
}

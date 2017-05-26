<?php

namespace WordsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CheckResult
 *
 * @ORM\Table(name="sy_check_result")
 * @ORM\Entity(repositoryClass="WordsBundle\Entity\CheckResultRepository")
 */
class CheckResult
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
     * @ORM\ManyToOne(targetEntity="PublicBundle\Entity\UploadFiles", inversedBy="result")
     * @ORM\JoinColumn(name="fid", referencedColumnName="id")
     */
    private $file;

    /**
     * @var string
     *
     * @ORM\Column(name="sheet", type="string", length=255)
     */
    private $sheet;

    /**
     * @var string
     *
     * @ORM\Column(name="cell", type="string", length=255)
     */
    private $cell;

    /**
     * @var string
     *
     * @ORM\Column(name="word", type="string", length=255)
     */
    private $word;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="createtime", type="datetime")
     */
    private $createTime;


    /**
     * CheckResult constructor.
     */
    public function __construct()
    {
        $this->createTime = new \DateTime;
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
     * @return CheckResult
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
     * Set sheet
     *
     * @param string $sheet
     * @return CheckResult
     */
    public function setSheet($sheet)
    {
        $this->sheet = $sheet;

        return $this;
    }

    /**
     * Get sheet
     *
     * @return string
     */
    public function getSheet()
    {
        return $this->sheet;
    }

    /**
     * Set cell
     *
     * @param string $cell
     * @return CheckResult
     */
    public function setCell($cell)
    {
        $this->cell = $cell;

        return $this;
    }

    /**
     * Get cell
     *
     * @return string
     */
    public function getCell()
    {
        return $this->cell;
    }

    /**
     * Set word
     *
     * @param string $word
     * @return CheckResult
     */
    public function setWord($word)
    {
        $this->word = $word;

        return $this;
    }

    /**
     * Get word
     *
     * @return string
     */
    public function getWord()
    {
        return $this->word;
    }

    /**
     * Set createTime
     *
     * @param \DateTime $createTime
     * @return CheckResult
     */
    public function setCreateTime($createTime)
    {
        $this->createTime = $createTime;

        return $this;
    }

    /**
     * Get createTime
     *
     * @return \DateTime
     */
    public function getCreateTime()
    {
        return $this->createTime;
    }
}

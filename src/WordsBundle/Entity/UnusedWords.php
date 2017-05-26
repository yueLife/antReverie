<?php

namespace WordsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * UnusedWords
 *
 * @ORM\Table(name="sy_unused_words")
 * @ORM\Entity(repositoryClass="WordsBundle\Entity\UnusedWordsRepository")
 */
class UnusedWords
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
     * @ORM\Column(name="word", type="string", length=255)
     */
    private $word;

    /**
     * @var integer
     *
     * @ORM\ManyToOne(targetEntity="PublicBundle\Entity\UploadFiles", inversedBy="unusedWord")
     * @ORM\JoinColumn(name="fid", referencedColumnName="id")
     */
    private $file;

    /**
     * @var integer
     *
     * @ORM\ManyToOne(targetEntity="UsersBundle\Entity\Users", inversedBy="addWord")
     * @ORM\JoinColumn(name="adder_id", referencedColumnName="id")
     */
    private $adder;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="addtime", type="datetime")
     */
    private $addTime;

    /**
     * @var integer
     *
     * @ORM\ManyToOne(targetEntity="UsersBundle\Entity\Users", inversedBy="delWord")
     * @ORM\JoinColumn(name="deleter_id", referencedColumnName="id")
     */
    private $deleter;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="deltime", type="datetime", nullable=true)
     */
    private $delTime;

    /**
     * @var boolean
     *
     * @ORM\Column(name="del", type="boolean")
     */
    private $del = false;


    /**
     * UnusedWords constructor.
     */
    public function __construct()
    {
        $this->addTime = new \DateTime;
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
     * Set word
     *
     * @param string $word
     * @return UnusedWords
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
     * Set adder
     *
     * @param \UsersBundle\Entity\Users $adder
     * @return UnusedWords
     */
    public function setAdder(\UsersBundle\Entity\Users $adder = null)
    {
        $this->adder = $adder;

        return $this;
    }

    /**
     * Get adder
     *
     * @return \UsersBundle\Entity\Users
     */
    public function getAdder()
    {
        return $this->adder;
    }

    /**
     * Set addTime
     *
     * @param \DateTime $addTime
     * @return UnusedWords
     */
    public function setAddTime($addTime)
    {
        $this->addTime = $addTime;

        return $this;
    }

    /**
     * Get addTime
     *
     * @return \DateTime
     */
    public function getAddTime()
    {
        return $this->addTime;
    }

    /**
     * Set deleter
     *
     * @param \UsersBundle\Entity\Users $deleter
     * @return UnusedWords
     */
    public function setDeleter(\UsersBundle\Entity\Users $deleter = null)
    {
        $this->deleter = $deleter;

        return $this;
    }

    /**
     * Get deleter
     *
     * @return \UsersBundle\Entity\Users
     */
    public function getDeleter()
    {
        return $this->deleter;
    }

    /**
     * Set delTime
     *
     * @param \DateTime $delTime
     * @return UnusedWords
     */
    public function setDelTime($delTime)
    {
        $this->delTime = $delTime;

        return $this;
    }

    /**
     * Get delTime
     *
     * @return \DateTime
     */
    public function getDelTime()
    {
        return $this->delTime;
    }

    /**
     * Set del
     *
     * @param boolean $del
     * @return UnusedWords
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
     * Set file
     *
     * @param \PublicBundle\Entity\UploadFiles $file
     * @return UnusedWords
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
}

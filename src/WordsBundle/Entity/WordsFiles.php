<?php

namespace WordsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use PublicBundle\Entity\FilesEntity;

/**
 * WordsFiles
 *
 * @ORM\Table(name="sy_words_files")
 * @ORM\Entity(repositoryClass="WordsBundle\Entity\WordsFilesRepository")
 */
class WordsFiles extends FilesEntity
{
    /**
     * @var string
     *
     * @ORM\Column(name="state", type="string", length=255)
     */
    private $state = 'unread';


    /**
     * Set state
     *
     * @param string $state
     * @return WordsFiles
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

<?php

namespace GoodsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use PublicBundle\Entity\FilesEntity;

/**
 * GoodsFiles
 *
 * @ORM\Table(name="sy_goods_files")
 * @ORM\Entity(repositoryClass="GoodsBundle\Entity\GoodsFilesRepository")
 */
class GoodsFiles extends FilesEntity
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

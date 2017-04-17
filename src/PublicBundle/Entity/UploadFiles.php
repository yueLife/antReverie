<?php

namespace PublicBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use PublicBundle\Entity\FilesEntity;

/**
 * UploadFiles
 *
 * @ORM\Table(name="sy_upload_files")
 * @ORM\Entity(repositoryClass="PublicBundle\Entity\UploadFilesRepository")
 */
class UploadFiles extends FilesEntity
{
}

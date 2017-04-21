<?php

namespace PublicBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * UploadFilesRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class UploadFilesRepository extends EntityRepository
{
    /**
     * Query the user is not deleted file
     *
     * @param Entity $user
     * @param string $extension
     * @return mixed
     */
    public function findByUserNotDel($user, $extension)
    {
        $fileInfo = $this->findBy(array('user' => $user, 'del' => false, 'extension' => $extension));
        return $fileInfo;
    }
}

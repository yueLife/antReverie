<?php
/**
 * Created by PhpStorm.
 * User: Yue
 * Date: 2017/4/5
 * Time: 15:18
 */

namespace UsersBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class UsersController
 *
 * @package UsersBundle\Controller
 * @Route("/users")
 */
class UsersController extends Controller
{
    /**
     * Show the user's file
     *
     * @Route("/files", name="userFile")
     * @Template("UsersBundle:Main:userFile.html.twig")
     */
    public function userFileAction()
    {
        $em = $this->getDoctrine()->getManager();
        $uploadFilesEm = $em->getRepository('PublicBundle:UploadFiles');
        $shelfModelsInfo = $em->getRepository('ShelfBundle:ShelfModels')->findBy(array('shopId' => 1, 'del' => false));

        $uploadFilesInfo = $goodsFilesInfo = $wordsFilesInfo = $unusedWordsFileInfo = [];
        if ($this->getUser()->hasRole('ROLE_GOODS')) {
            $goodsFilesInfo = $uploadFilesEm->findByUserNotDel($this->getUser(), 'goodsFile');
            $uploadFilesInfo = array_merge($uploadFilesInfo, $goodsFilesInfo);
        }
        if ($this->getUser()->hasRole('ROLE_WORDS')) {
            $wordsFilesInfo = $uploadFilesEm->findByUserNotDel($this->getUser(), 'wordsFile');
            $unusedWordsFileInfo = $uploadFilesEm->findByUserNotDel($this->getUser(), 'unusedWordsFile');
            $uploadFilesInfo = array_merge($uploadFilesInfo, $wordsFilesInfo, $unusedWordsFileInfo);
        }

        return array(
            'name' => 'file',
            'uploadFiles' => $uploadFilesInfo,
            'shelfModels' => $shelfModelsInfo,
        );
    }

    /**
     * Show all files
     *
     * @Route("/allFiles", name="allFiles")
     * @Template("UsersBundle:Main:allFiles.html.twig")
     */
    public function allFilesAction()
    {
        $em = $this->getDoctrine()->getManager();
        $uploadFilesEm = $em->getRepository('PublicBundle:UploadFiles');
        $uploadFilesInfo = $uploadFilesEm->findByDel(false);

        return array(
            'name' => 'file',
            'uploadFiles' => $uploadFilesInfo,
        );
    }
}

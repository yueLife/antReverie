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
 * @Route("/user")
 */
class PublicController extends Controller
{
    /**
     * @Route("/file", name="userFile")
     * @Template("UsersBundle:Main:userFile.html.twig")
     */
    public function userFileAction()
    {
        $em = $this->getDoctrine()->getManager();
        $uploadFilesEm = $em->getRepository('PublicBundle:UploadFiles');
        $styleTypesInfo = $em->getRepository('GoodsBundle:StyleTypes')->findBy(array('shopId' => 1, 'del' => false));
        $uploadFilesInfo = $uploadFilesEm->findByUser($this->getUser());

        return array(
            'name' => 'file',
            'uploadFiles' => $uploadFilesInfo,
            'styleTypes' => $styleTypesInfo,
        );
    }

    /**
     * @Route("/files", name="userFiles")
     * @Template("UsersBundle:Main:userFiles.html.twig")
     */
    public function userFilesAction()
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

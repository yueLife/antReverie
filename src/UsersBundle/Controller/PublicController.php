<?php
/**
 * Created by PhpStorm.
 * User: Yue
 * Date: 2017/4/5
 * Time: 15:18
 */

namespace UsersBundle\Controller;

use PublicBundle\Entity\UploadFiles;
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
        $goodsFilesEm = $em->getRepository('GoodsBundle:GoodsFiles');
        $styleTypesEm = $em->getRepository('GoodsBundle:StyleTypes');
        $goodsFilesInfo = $goodsFilesEm->findBy(array('user' => $this->getUser(), 'del' => false));
        $styleTypesInfo = $styleTypesEm->findBy(array('shopId' => 1, 'del' => false));

        return array(
            'name' => 'file',
            'goodsFiles' => $goodsFilesInfo,
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
        $goodsFilesEm = $em->getRepository('GoodsBundle:GoodsFiles');
        $goodsFilesInfo = $goodsFilesEm->findByDel(false);

        return array(
            'name' => 'file',
            'goodsFiles' => $goodsFilesInfo,
        );
    }
}

<?php

namespace UsersBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/public")
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
        $uid = $this->getUser()->getId();
        $goodsFilesInfo = $goodsFilesEm->findByUid($uid);

        return array(
            'name' => 'file',
            'goodsFiles' => $goodsFilesInfo,
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
        $goodsFilesInfo = $goodsFilesEm->findAll();

        return array(
            'name' => 'file',
            'goodsFiles' => $goodsFilesInfo,
        );
    }
}

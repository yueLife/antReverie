<?php

namespace PublicBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use PublicBundle\Controller\UploadController as Upload;
use PublicBundle\Entity\UploadFiles;

class HomeController extends Controller
{
    /**
     * @Route("/", name="home")
     * @Template("PublicBundle:Home:index.html.twig")
     */
    public function indexAction(Request $request)
    {
        return array('name' => 'name');
    }

    /**
     * @Route("/upload", name="upload")
     */
    public function uploadAction()
    {
        $uploadData = new Upload();
        $data = $uploadData->response['files'][0];

        if (empty($data->error)) {
            $filename = 'file_'.uniqid().'.'.pathinfo($data->name, PATHINFO_EXTENSION);
            $root = $_SERVER['DOCUMENT_ROOT'].'/Uploads/';
            if (rename($root.'data/'.$data->name, $root.'goods/'.$filename)) {
                $em = $this->getDoctrine()->getEntityManager();
                $newFile = new UploadFiles();
                $newFile->setUid($this->getUser()->getId())->setFilename($filename)->setOldname($data->name);
                $em->persist($newFile);
                $em->flush();
            }
        }

        return new JsonResponse($uploadData->response);
    }
}

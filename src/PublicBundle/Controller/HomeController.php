<?php

namespace PublicBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use PublicBundle\Controller\UploadController as Upload;

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
        $url = 'goods/';
        $uploadData = new Upload($url);
        return new JsonResponse($uploadData->response);
    }
}

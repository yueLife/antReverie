<?php

namespace PublicBundle\Controller;

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
     * @Route("/index", name="publicIndex")
     * @Template("PublicBundle:Main:publicIndex.html.twig")
     */
    public function indexAction()
    {
        return array('name' => 'name');
    }
}

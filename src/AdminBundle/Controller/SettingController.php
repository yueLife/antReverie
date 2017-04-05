<?php

namespace AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class SettingController extends Controller
{
    /**
     * @Route("/dashboard", name="dashboard")
     * @Template("AdminBundle:Setting:dashboard.html.twig")
     */
    public function indexAction()
    {
        return array('name' => 'name');
    }
}

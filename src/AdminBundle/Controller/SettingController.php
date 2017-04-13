<?php
/**
 * Created by PhpStorm.
 * User: Yue
 * Date: 2017/4/1
 * Time: 10:57
 */

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

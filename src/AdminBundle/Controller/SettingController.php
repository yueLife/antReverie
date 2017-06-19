<?php
/**
 * Created by PhpStorm.
 * User: Yue
 * Date: 2017/4/1
 * Time: 10:57
 */

namespace AdminBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * Class SettingController
 *
 * @package AdminBundle\Controller
 * @Route("/profile/admin")
 */
class SettingController extends Controller
{
    /**
     * Users List Index
     *
     * @Route("/usersList", name="usersList")
     * @Template("AdminBundle::Main/usersList.html.twig")
     * @return array
     */
    public function usersListAction()
    {
        $em = $this->getDoctrine()->getManager();
        $usersRepo = $em->getRepository("UsersBundle:Users");
        $usersData = $usersRepo->findAll();
        $shopsRepo = $em->getRepository("ShelfBundle:Shops");
        $shopsData = $shopsRepo->findAll();


        return array(
            "users" => $usersData,
            "shops" => $shopsData
        );
    }
}

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

    /**
     * Shops List Index
     *
     * @Route("/shopsList", name="shopsList")
     * @Template("AdminBundle::Main/shopsList.html.twig")
     * @return array
     */
    public function shopsListAction()
    {
        $em = $this->getDoctrine()->getManager();
        $shopsRepo = $em->getRepository("ShelfBundle:Shops");
        $shopsData = $shopsRepo->findAll();
        $brandsRepo = $em->getRepository("ShelfBundle:Brands");
        $brandsData = $brandsRepo->findAll();
        $platsRepo = $em->getRepository("ShelfBundle:Plats");
        $platsData = $platsRepo->findAll();

        return array(
            "shops" => $shopsData,
            "brands" => $brandsData,
            "plats" => $platsData
        );
    }

    /**
     * Brands List Index
     *
     * @Route("/brandsList", name="brandsList")
     * @Template("AdminBundle::Main/brandsList.html.twig")
     * @return array
     */
    public function brandsListAction()
    {
        $em = $this->getDoctrine()->getManager();
        $brandsRepo = $em->getRepository("ShelfBundle:Brands");
        $brandsData = $brandsRepo->findAll();

        return array(
            "brands" => $brandsData
        );
    }

    /**
     * Plats List Index
     *
     * @Route("/platsList", name="platsList")
     * @Template("AdminBundle::Main/platsList.html.twig")
     * @return array
     */
    public function platsListAction()
    {
        $em = $this->getDoctrine()->getManager();
        $platsRepo = $em->getRepository("ShelfBundle:Plats");
        $platsData = $platsRepo->findAll();

        return array(
            "plats" => $platsData
        );
    }

    /**
     * Set white list
     *
     * @Route("/whiteList", name="whiteList")
     * @Template("AdminBundle::Setting/whiteList.html.twig")
     */
    public function whiteListAction()
    {

    }
}

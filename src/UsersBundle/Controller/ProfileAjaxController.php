<?php
/**
 * Created by PhpStorm.
 * User: Yue
 * Date: 2017/5/24
 * Time: 10:35
 */

namespace UsersBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class ProfileAjaxController
 *
 * @package UsersBundle\Controller
 * @Route("/ajax/profile")
 */
class ProfileAjaxController extends Controller
{
    /**
     * Change user's current shop
     *
     * @Route("/changeUserShop", name="changeUserShopAjax")
     * @param Request $request
     * @return JsonResponse
     */
    public function changeUserShopAjax(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $shopsEm = $em->getRepository("ShelfBundle:Shops");
        $shelfUsersEm = $em->getRepository("ShelfBundle:ShelfUsers");
        if (!$request->get("shopId")) {
            return new JsonResponse("error");
        }
        $shopData = $shopsEm->findOneById($request->get("shopId"));
        $brand = $shopData->getBrand();
        $plat = $shopData->getPlat();

        $shelfUserData = $shelfUsersEm->findOneByUser($this->getUser());
        $shelfUserData->setPlat($plat)->setBrand($brand);
        $em->flush();

        return new JsonResponse("success");
    }
}
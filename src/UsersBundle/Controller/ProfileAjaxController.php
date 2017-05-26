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
 * @Route("/profile/ajax")
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

        return new JsonResponse(array('state' => 'success'));
    }

    /**
     * Change user's password
     *
     * @Route("/changePasswd", name="changePasswdAjax")
     * @param Request $request
     * @return JsonResponse
     */
    public function changePasswdAjax(Request $request)
    {
        $validation = $this->validatePasswdFunc($request->get("oldPasswd"), $request->get("newPasswd"), $request->get("rePasswd"));

        if ($validation["state"] === "error") {
            return new JsonResponse($validation);
        }

        $em = $this->getDoctrine()->getManager();
        $options = array('cost' => 13, 'salt' => $this->getUser()->getSalt());
        $this->getUser()->setPassword(password_hash($request->get("rePasswd"), PASSWORD_BCRYPT, $options));
        $em->flush();

        return new JsonResponse(array('state' => 'success'));
    }


    /**
     * Validate password
     *
     * @param string $oldPasswd
     * @param string $newPasswd
     * @param string $rePasswd
     * @return array
     */
    public function validatePasswdFunc($oldPasswd, $newPasswd, $rePasswd)
    {
        $verification = password_verify($oldPasswd, $this->getUser()->getPassword());
        if (!$verification) {
            return array('state' => 'error', 'msg' => '旧密码输入错误！请重新输入！');
        } else if ($newPasswd !== $rePasswd) {
            return array('state' => 'error', 'msg' => '两次密码不一致！请重新输入！');
        } else if (strlen($rePasswd) < 6 || strlen($rePasswd) > 12) {
            return array('state' => 'error', 'msg' => '密码长度应为6-12位！请重新输入！');
        } else if (!preg_match("/^(\w)*$/", $rePasswd)) {
            return array('state' => 'error', 'msg' => '密码长度必须为数字、字母或下划线!');
        } else {
            return array('state' => 'success');
        }
    }
}
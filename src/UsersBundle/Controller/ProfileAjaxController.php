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
        $shopsRepo = $em->getRepository("ShelfBundle:Shops");
        $shelfUsersRepo = $em->getRepository("ShelfBundle:ShelfUsers");
        if (!$request->get("shopId")) return new JsonResponse("error");

        $shopData = $shopsRepo->findOneById($request->get("shopId"));
        $shelfUserData = $shelfUsersRepo->findOneByUser($this->getUser());
        $shelfUserData->setPlat($shopData->getPlat())->setBrand($shopData->getBrand());
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

        if ($validation["state"] === "error") return new JsonResponse($validation);

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
            return $this->get("getmessageservice")->getUserMsg("oldPasswordError");
        } else if ($newPasswd !== $rePasswd) {
            return $this->get("getmessageservice")->getUserMsg("passwordError");
        } else if (strlen($rePasswd) < 6 || strlen($rePasswd) > 12) {
            return $this->get("getmessageservice")->getUserMsg("passwordLengthError");
        } else if (!preg_match("/^(\w)*$/", $rePasswd)) {
            return $this->get("getmessageservice")->getUserMsg("passwordTypeError");
        } else {
            return array('state' => 'success');
        }
    }
}
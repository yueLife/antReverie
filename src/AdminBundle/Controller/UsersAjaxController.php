<?php
/**
 * Created by PhpStorm.
 * User: Yue
 * Date: 2017/6/12
 * Time: 14:28
 */

namespace AdminBundle\Controller;

use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use UsersBundle\Entity\Users;

/**
 * Class UsersAjaxController
 *
 * @package AdminBundle\Controller
 * @Route("/profile/admin/ajax")
 */
class UsersAjaxController extends Controller
{
    /**
     * Lock the user
     *
     * @Route("/lockUser", name="lockUserAjax")
     * @param Request $request
     * @return JsonResponse
     */
    public function lockUserAjax(Request $request)
    {
        $em = $this->getDoctrine()->getEntityManager();
        $usersRepo = $em->getRepository("UsersBundle:Users");

        $userData = $usersRepo->findOneBy(array("id" => $request->get("id")));
        $userData->setLocked(!$userData->isLocked());
        $em->flush();

        return new JsonResponse($this->get("getmessageservice")->getGeneralMsg("setSuccess"));
    }

    /**
     * Add role
     *
     * @Route("/setRole", name="setRoleAjax")
     * @param Request $request
     * @return JsonResponse
     */
    public function setRoleAjax(Request $request)
    {
        $em = $this->getDoctrine()->getEntityManager();
        $usersRepo = $em->getRepository("UsersBundle:Users");

        $userData = $usersRepo->findOneBy(array("id" => $request->get("id")));
        switch ($request->get("action")) {
            case "add":
                $userData->addRole($request->get("role")); break;
            case "del":
                $userData->removeRole($request->get("role")); break;
            default:
                return new JsonResponse($this->get("getmessageservice")->getGeneralMsg("addFailed"));
        }
        $em->flush();

        return new JsonResponse($this->get("getmessageservice")->getGeneralMsg("addSuccess"));
    }

    /**
     * Get edit user
     *
     * @Route("/getEditUser", name="getEditUserAjax")
     * @param Request $request
     * @return JsonResponse
     */
    public function getEditUserAjax(Request $request)
    {
        $em = $this->getDoctrine()->getEntityManager();
        $query = $em->createQuery(
            "SELECT u FROM UsersBundle:Users As u WHERE u.id = ".$request->get("id"));
        $userData = $query->getArrayResult()[0];

        $shops = false;
        $usersRepo = $em->getRepository("UsersBundle:Users");
        if ($shopsData = $usersRepo->findOneBy(array("id" => $request->get("id")))->getShops()) {
            foreach ($shopsData as $key => $shop) {
                $shops[$key] = $shopsData[$key]->getId();
            }
        }

        return new JsonResponse(array(
            "user" => $userData,
            "shops" => $shops
        ));
    }

    /**
     * Put edit user
     *
     * @Route("/putEditUser", name="putEditUserAjax")
     * @param Request $request
     * @return JsonResponse
     */
    public function putEditUserAjax(Request $request)
    {
        $em = $this->getDoctrine()->getEntityManager();
        $usersRepo = $em->getRepository("UsersBundle:Users");
        $userEntity = $usersRepo->findOneBy(array("id" => $request->get("id")));

        if ($request->get("rePassword") !== $request->get("password")) {
            return new JsonResponse($this->get("getmessageservice")->getUserMsg("passwordError"));
        } else if (strlen($request->get("rePassword")) < 6 || strlen($request->get("rePassword")) > 12) {
            return new JsonResponse($this->get("getmessageservice")->getUserMsg("passwordLengthError"));
        } else if (!preg_match("/^(\w)*$/", $request->get("rePassword"))) {
            return new JsonResponse($this->get("getmessageservice")->getUserMsg("passwordTypeError"));
        }

        $em->getConnection()->beginTransaction();
        try {
            $userEntity->setUsername($request->get("username"))->setEmail($request->get("email"));
            if ($userShops = $userEntity->getShops()) {
                $shopsRepo = $em->getRepository("ShelfBundle:Shops");

                foreach ($userShops as $key => $shop) {
                    $userEntity->removeShop($shop);
                    $shop->removeUser($userEntity);
                }

                foreach ($request->get("shops") as $shopId) {
                    $shopEntity = $shopsRepo->findOneBy(array("id" => $shopId));
                    $userEntity->addShop($shopEntity);
                    $shopEntity->addUser($userEntity);
                }
            }
            $em->flush();
            $em->getConnection()->commit();
        } catch (UniqueConstraintViolationException $e) {
            $errorMsg = $e->getPrevious()->errorInfo[2];
            if (strpos($errorMsg, $request->get("username"))) {
                return new JsonResponse($this->get("getmessageservice")->getUserMsg("uniqueUsername"));
            }
            if (strpos($errorMsg, $request->get("email"))) {
                return new JsonResponse($this->get("getmessageservice")->getUserMsg("uniqueEmail"));
            }

            $em->getConnection()->rollback();
            $em->close();
            return new JsonResponse($this->get("getmessageservice")->getGeneralMsg("unknown"));
        } catch (\Exception $e) {
            $em->getConnection()->rollback();
            $em->close();
            return new JsonResponse($this->get("getmessageservice")->getGeneralMsg("unknown"));
        }

        return new JsonResponse($this->get("getmessageservice")->getUserMsg("editSuccess"));
    }

    /**
     * Add new user
     *
     * @Route("/addNewUser", name="addNewUserAjax")
     * @param Request $request
     * @return JsonResponse
     */
    public function addNewUserAjax(Request $request)
    {
        $em = $this->getDoctrine()->getEntityManager();
        $em->getConnection()->beginTransaction();

        if ($request->get("rePassword") !== $request->get("password")) {
            return new JsonResponse($this->get("getmessageservice")->getUserMsg("passwordError"));
        } else if (strlen($request->get("rePassword")) < 6 || strlen($request->get("rePassword")) > 12) {
            return new JsonResponse($this->get("getmessageservice")->getUserMsg("passwordLengthError"));
        } else if (!preg_match("/^(\w)*$/", $request->get("rePassword"))) {
            return new JsonResponse($this->get("getmessageservice")->getUserMsg("passwordTypeError"));
        }

        try {
            $newUser = new Users();
            $options = array('cost' => 13, 'salt' => $newUser->getSalt());
            $newUser->setUsername($request->get("username"))
                ->setEmail($request->get("email"))
                ->setPassword(password_hash($request->get("rePassword"), PASSWORD_BCRYPT, $options))
                ->addRole("ROLE_GENERAL");

            $shopsRepo = $em->getRepository("ShelfBundle:Shops");

            foreach ($request->get("shops") as $shopId) {
                $shopEntity = $shopsRepo->findOneBy(array("id" => $shopId));
                $newUser->addShop($shopEntity);
                $shopEntity->addUser($newUser);
            }
            $em->persist($newUser);
            $em->flush();
            $em->getConnection()->commit();
        } catch (UniqueConstraintViolationException $e) {
            $errorMsg = $e->getPrevious()->errorInfo[2];
            if (strpos($errorMsg, $request->get("username"))) {
                return new JsonResponse($this->get("getmessageservice")->getUserMsg("uniqueUsername"));
            }
            if (strpos($errorMsg, $request->get("email"))) {
                return new JsonResponse($this->get("getmessageservice")->getUserMsg("uniqueEmail"));
            }

            $em->getConnection()->rollback();
            $em->close();
            return new JsonResponse($this->get("getmessageservice")->getGeneralMsg("unknown"));
        } catch (\Exception $e) {
            $em->getConnection()->rollback();
            $em->close();
            return new JsonResponse($this->get("getmessageservice")->getGeneralMsg("unknown"));
        }

        return new JsonResponse($this->get("getmessageservice")->getUserMsg("addSuccess"));
    }
}

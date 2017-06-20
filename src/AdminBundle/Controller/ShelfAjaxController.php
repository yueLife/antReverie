<?php
/**
 * Created by PhpStorm.
 * User: Yue
 * Date: 2017/6/20
 * Time: 9:25
 */

namespace AdminBundle\Controller;

use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use ShelfBundle\Entity\Brands;
use ShelfBundle\Entity\Plats;
use ShelfBundle\Entity\Shops;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class ShelfAjaxController
 *
 * @package AdminBundle\Controller
 * @Route("/profile/admin/ajax")
 */
class ShelfAjaxController extends Controller
{

    /**
     * Add new brand
     *
     * @Route("/addBrand", name="addBrandAjax")
     * @param Request $request
     * @return JsonResponse
     */
    public function addBrandAjax(Request $request)
    {
        $em = $this->getDoctrine()->getEntityManager();
        $em->getConnection()->beginTransaction();

        try {
            $newBrand = new Brands();
            $newBrand->setBrandname($request->get("brandname"));
            $em->persist($newBrand);
            $em->flush();
            $em->getConnection()->commit();
        } catch (UniqueConstraintViolationException $e) {
            $em->getConnection()->rollback();
            $em->close();
            return new JsonResponse($this->get("getmessageservice")->getShelfMsg("uniqueBrandname"));
        } catch (\Exception $e) {
            $em->getConnection()->rollback();
            $em->close();
            return new JsonResponse($this->get("getmessageservice")->getGeneralMsg("unknown"));
        }

        $array = array(
            "id" => $newBrand->getId(),
            "brandname" => $newBrand->getBrandname(),
            "createTime" => $newBrand->getCreateTime()->format("Y-m-d H:i:s"),
        );
        return new JsonResponse(array_merge($array, $this->get("getmessageservice")->getShelfMsg("addSuccess")));
    }

    /**
     * Edit brand information
     *
     * @Route("/editBrand", name="editBrandAjax")
     * @param Request $request
     * @return JsonResponse
     */
    public function editBrandAjax(Request $request)
    {
        $em = $this->getDoctrine()->getEntityManager();
        $em->getConnection()->beginTransaction();

        try {
            $brandRepo = $em->getRepository("ShelfBundle:Brands");
            $brandEntity = $brandRepo->findOneBy(array("id" => $request->get("id")));
            $brandEntity->setBrandname($request->get("brandname"));
            $em->flush();
            $em->getConnection()->commit();
        } catch (UniqueConstraintViolationException $e) {
            $em->getConnection()->rollback();
            $em->close();
            return new JsonResponse($this->get("getmessageservice")->getShelfMsg("uniqueBrandname"));
        } catch (\Exception $e) {
            $em->getConnection()->rollback();
            $em->close();
            return new JsonResponse($this->get("getmessageservice")->getGeneralMsg("unknown"));
        }
        return new JsonResponse($this->get("getmessageservice")->getShelfMsg("editSuccess"));
    }

    /**
     * Lock brand information
     *
     * @Route("/lockBrand", name="lockBrandAjax")
     * @param Request $request
     * @return JsonResponse
     */
    public function lockBrandAjax(Request $request)
    {
        $em = $this->getDoctrine()->getEntityManager();
        $brandRepo = $em->getRepository("ShelfBundle:Brands");

        $brandData = $brandRepo->findOneBy(array("id" => $request->get("id")));
        $brandData->setActive(!$brandData->getActive());
        $em->flush();

        return new JsonResponse($this->get("getmessageservice")->getGeneralMsg("setSuccess"));
    }

    /**
     * Add new plat
     *
     * @Route("/addPlat", name="addPlatAjax")
     * @param Request $request
     * @return JsonResponse
     */
    public function addPlatAjax(Request $request)
    {
        $em = $this->getDoctrine()->getEntityManager();
        $em->getConnection()->beginTransaction();

        try {
            $newPlat = new Plats();
            $newPlat->setPlatname($request->get("platname"));
            $em->persist($newPlat);
            $em->flush();
            $em->getConnection()->commit();
        } catch (UniqueConstraintViolationException $e) {
            $em->getConnection()->rollback();
            $em->close();
            return new JsonResponse($this->get("getmessageservice")->getShelfMsg("uniquePlatname"));
        } catch (\Exception $e) {
            $em->getConnection()->rollback();
            $em->close();
            return new JsonResponse($this->get("getmessageservice")->getGeneralMsg("unknown"));
        }

        $array = array(
            "id" => $newPlat->getId(),
            "platname" => $newPlat->getPlatname(),
            "createTime" => $newPlat->getCreateTime()->format("Y-m-d H:i:s"),
        );
        return new JsonResponse(array_merge($array, $this->get("getmessageservice")->getShelfMsg("addSuccess")));
    }

    /**
     * Get Plat information
     *
     * @Route("/getPlat", name="getPlatAjax")
     * @param Request $request
     * @return JsonResponse
     */
    public function getPlatAjax(Request $request)
    {
        $em = $this->getDoctrine()->getEntityManager();
        $platRepo = $em->getRepository("ShelfBundle:Plats");
        $platEntity = $platRepo->findOneBy(array("id" => $request->get("id")));

        return new JsonResponse($platEntity->getUrl());
    }

    /**
     * Edit plat information
     *
     * @Route("/editPlat", name="editPlatAjax")
     * @param Request $request
     * @return JsonResponse
     */
    public function editPlatAjax(Request $request)
    {
        $em = $this->getDoctrine()->getEntityManager();
        $em->getConnection()->beginTransaction();

        try {
            $platRepo = $em->getRepository("ShelfBundle:Plats");
            $platEntity = $platRepo->findOneBy(array("id" => $request->get("id")));
            $jsonUrl = json_encode(array(
                "start" => $request->get("platStart"),
                "end" => $request->get("platEnd"),
            ));
            $platEntity->setPlatname($request->get("platname"))->setUrl($jsonUrl);
            $em->flush();
            $em->getConnection()->commit();
        } catch (UniqueConstraintViolationException $e) {
            $em->getConnection()->rollback();
            $em->close();
            return new JsonResponse($this->get("getmessageservice")->getShelfMsg("uniquePlatname"));
        } catch (\Exception $e) {
            $em->getConnection()->rollback();
            $em->close();
            return new JsonResponse($this->get("getmessageservice")->getGeneralMsg("unknown"));
        }
        return new JsonResponse($this->get("getmessageservice")->getShelfMsg("editSuccess"));
    }

    /**
     * Lock plat information
     *
     * @Route("/lockPlat", name="lockPlatAjax")
     * @param Request $request
     * @return JsonResponse
     */
    public function lockPlatAjax(Request $request)
    {
        $em = $this->getDoctrine()->getEntityManager();
        $platRepo = $em->getRepository("ShelfBundle:Plats");

        $platData = $platRepo->findOneBy(array("id" => $request->get("id")));
        $platData->setActive(!$platData->getActive());
        $em->flush();

        return new JsonResponse($this->get("getmessageservice")->getGeneralMsg("setSuccess"));
    }

    /**
     * Lock shop information
     *
     * @Route("/lockShop", name="lockShopAjax")
     * @param Request $request
     * @return JsonResponse
     */
    public function lockShopAjax(Request $request)
    {
        $em = $this->getDoctrine()->getEntityManager();
        $shopRepo = $em->getRepository("ShelfBundle:Shops");

        $shopData = $shopRepo->findOneBy(array("id" => $request->get("id")));
        $shopData->setActive(!$shopData->getActive());
        $em->flush();

        return new JsonResponse($this->get("getmessageservice")->getGeneralMsg("setSuccess"));
    }

    /**
     * Edit shop information
     *
     * @Route("/editShop", name="editShopAjax")
     * @param Request $request
     * @return JsonResponse
     */
    public function editShopAjax(Request $request)
    {
        $em = $this->getDoctrine()->getEntityManager();
        $em->getConnection()->beginTransaction();

        try {
            $shopRepo = $em->getRepository("ShelfBundle:Shops");
            $platRepo = $em->getRepository("ShelfBundle:Plats");
            $brandRepo = $em->getRepository("ShelfBundle:Brands");

            $shopEntity = $shopRepo->findOneBy(array("id" => $request->get("id")));
            $platEntity = $platRepo->findOneBy(array("id" => $request->get("plat")));
            $brandEntity = $brandRepo->findOneBy(array("id" => $request->get("brand")));
            $shopEntity->setShopname($request->get("shopname"))->setBrand($brandEntity)->setPlat($platEntity);
            $em->flush();
            $em->getConnection()->commit();
        } catch (UniqueConstraintViolationException $e) {
            $em->getConnection()->rollback();
            $em->close();
            return new JsonResponse($this->get("getmessageservice")->getShelfMsg("uniqueShopname"));
        } catch (\Exception $e) {
            $em->getConnection()->rollback();
            $em->close();
            return new JsonResponse($this->get("getmessageservice")->getGeneralMsg("unknown"));
        }
        $array = array(
            "shop" => $shopEntity->getId(),
            "platname" => $platEntity->getPlatname(),
            "platId" => $platEntity->getId(),
            "brandname" => $brandEntity->getBrandname(),
            "brandId" => $brandEntity->getId()
        );
        return new JsonResponse(array_merge($array, $this->get("getmessageservice")->getShelfMsg("editSuccess")));
    }


    /**
     * Add shop information
     *
     * @Route("/addShop", name="addShopAjax")
     * @param Request $request
     * @return JsonResponse
     */
    public function addShopAjax(Request $request)
    {
        $em = $this->getDoctrine()->getEntityManager();
        $em->getConnection()->beginTransaction();

        try {
            $platRepo = $em->getRepository("ShelfBundle:Plats");
            $brandRepo = $em->getRepository("ShelfBundle:Brands");

            $platEntity = $platRepo->findOneBy(array("id" => $request->get("plat")));
            $brandEntity = $brandRepo->findOneBy(array("id" => $request->get("brand")));
            $newShop = new Shops();
            $newShop->setShopname($request->get("shopname"))->setBrand($brandEntity)->setPlat($platEntity);
            $em->persist($newShop);
            $em->flush();
            $em->getConnection()->commit();
        } catch (UniqueConstraintViolationException $e) {
            $em->getConnection()->rollback();
            $em->close();
            return new JsonResponse($this->get("getmessageservice")->getShelfMsg("uniqueShopname"));
        } catch (\Exception $e) {
            $em->getConnection()->rollback();
            $em->close();
            return new JsonResponse($this->get("getmessageservice")->getGeneralMsg("unknown"));
        }

        $array = array(
            "shop" => $newShop->getId(),
            "shopname" => $newShop->getShopname(),
            "platname" => $platEntity->getPlatname(),
            "platId" => $platEntity->getId(),
            "brandname" => $brandEntity->getBrandname(),
            "brandId" => $brandEntity->getId(),
            "createTime" => $newShop->getCreateTime()->format("Y-m-d H:i:s")
        );
        return new JsonResponse(array_merge($array, $this->get("getmessageservice")->getShelfMsg("addSuccess")));
    }
}
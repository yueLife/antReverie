<?php
/**
 * Created by PhpStorm.
 * User: Yue
 * Date: 2017/6/7
 * Time: 13:41
 */

namespace MichelinBundle\Controller;

use Doctrine\ORM\EntityManager;
use MichelinBundle\Entity\MichelinCities;
use MichelinBundle\Entity\MichelinStores;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class MichelinAjacController
 * @package MichelinBundle\Controller
 * @Route("/michelin/ajax")
 */
class MichelinAjacController extends Controller
{
    // 有关米其林门店的ajax
    /**
     * 添加门店城市
     *
     * @Route("/addStoreCity", name="addStoreCityAjax")
     */
    public function addStoreCityAjax(Request $request)
    {
        $jsonList = json_decode($request->get('json'));
        $em = $this->getDoctrine()->getEntityManager();
        $mclCityEm = $em->getRepository("MichelinBundle:MichelinCities");
        $province = $mclCityEm->findOneBy(array(
            'cityList' => $jsonList->listNum,
            'areaId' => $jsonList->parentId,
        ));

        if ($province) {
            $pid = $province->getId();
            $action = 'none';
        }else{
            $areasEm = $em->getRepository("PublicBundle:Areas");
            $newProv = $areasEm->findOneById($jsonList->parentId);
            $newMclCity = new MichelinCities();
            $newMclCityInfo = $newMclCity->setPid(0)
                ->setCityList($jsonList->listNum)
                ->setAreaId($newProv->getId())
                ->setAreaname($newProv->getAreaname())
                ->setAreaSpell($newProv->getAreaSpell());
            $em->persist($newMclCity);
            $em->flush();
            $action = 'reload';
            $pid = $newMclCityInfo->getId();
        }

        $newMclCity = new MichelinCities();
        $newMclCity->setPid($pid)
            ->setCityList($jsonList->listNum)
            ->setAreaId($jsonList->areaId)
            ->setAreaname($jsonList->name)
            ->setAreaSpell($jsonList->spell);
        $em->persist($newMclCity);
        $em->flush();

        return new JsonResponse(array('action' => $action));
    }

    /**
     * 删除门店城市
     *
     * @Route("/delStoreCity", name="delStoreCityAjax")
     */
    public function delStoreCityAjax(Request $request)
    {
        $jsonList = json_decode($request->get('json'));
        $em = $this->getDoctrine()->getEntityManager();
        $mclCityEm = $em->getRepository("MichelinBundle:MichelinCities");

        $mclCityInfo = $mclCityEm->findOneByAreaId($jsonList->cityId);
        $provinceID = $mclCityInfo->getPid();
        $em->remove($mclCityInfo);

        $count = count($mclCityEm->findByPid($provinceID));
        if ($count == 0) {
            $mclCityInfo = $mclCityEm->findOneById($provinceID);
            $em->remove($mclCityInfo);
        }
        $em->flush();
        return new JsonResponse(array('state' => 'success'));
    }

    /**
     * 添加门店
     *
     * @Route("/addStore", name="addStoreAjax")
     */
    public function addStoreAjax(Request $request)
    {
        $jsonList = json_decode($request->get('json'));
        $em = $this->getDoctrine()->getEntityManager();
        $newMclStore = new MichelinStores();
        $newMclStoreInfo = $newMclStore->setCid($jsonList->cid)
            ->setNumber($jsonList->number)
            ->setStorename($jsonList->name)
            ->setAddress($jsonList->address);
        $em->persist($newMclStore);
        $em->flush();

        return new JsonResponse(array(
            'state' => 'success',
            'id' => $newMclStoreInfo->getId()
        ));
    }

    /**
     * 删除门店
     *
     * @Route("/delStore", name="delStoreAjax")
     */
    public function delStoreAjax(Request $request)
    {
        $jsonList = json_decode($request->get('json'));
        $em = $this->getDoctrine()->getEntityManager();
        $mclStoresEm = $em->getRepository("MichelinBundle:MichelinStores");
        $mclStoresInfo = $mclStoresEm->findOneById($jsonList->storeId);
        $em->remove($mclStoresInfo);
        $em->flush();

        return new JsonResponse(array('state' => 'success'));
    }

    /**
     * 编辑门店信息
     *
     * @Route("/editStore", name="editStoreAjax")
     */
    public function editStoreAjax(Request $request)
    {
        $jsonList = json_decode($request->get('json'));
        $em = $this->getDoctrine()->getEntityManager();
        $mclStoresEm = $em->getRepository("MichelinBundle:MichelinStores");

        $mclStoresEm->findOneById($jsonList->id)
            ->setNumber($jsonList->number)
            ->setStorename($jsonList->name)
            ->setAddress($jsonList->address);
        $em->flush();

        return new JsonResponse(array('state' => 'success'));
    }

}
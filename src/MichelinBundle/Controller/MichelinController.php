<?php
/**
 * Created by PhpStorm.
 * User: Yue
 * Date: 2017/6/7
 * Time: 13:41
 */

namespace MichelinBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class MichelinController
 * @package MichelinBundle\Controller
 * @Route("/michelin")
 */
class MichelinController extends Controller
{
    /**
     * @Route("/TMStore/{listNum}", name="TMStore", defaults={"listNum" : 1}, requirements={"listNum" = "-*\d+"})
     * @Template("MichelinBundle::Main/TMStore.html.twig")
     * @return array
     */
    public function TMStoreListAction($listNum)
    {
        $em = $this->getDoctrine()->getEntityManager();
        $areasEm = $em->getRepository("PublicBundle:Areas");
        $mclCityEm = $em->getRepository("MichelinBundle:MichelinCities");
        $mclStoresEm = $em->getRepository("MichelinBundle:MichelinStores");

        $province = $areasEm->findBy(array('areaType' => 1));
        $city = $areasEm->findBy(array('areaType' => 2));
        $provGroup = $mclCityEm->findBy(array('cityList' => $listNum, 'pid' => 0));
        $provGroup = $mclCityEm->createQueryBuilder('m')
            ->where('m.cityList = :cityList')
            ->andWhere('m.pid = :pid')
            ->setParameters(
                array(
                    'cityList' => $listNum,
                    'pid' => 0,
                ))
            ->getQuery()
            ->getArrayResult();
        $cityGroup = $mclCityEm->createQueryBuilder('m')
            ->where('m.cityList = :cityList')
            ->andWhere('m.pid <> :pid')
            ->setParameters(
                array(
                    'cityList' => $listNum,
                    'pid' => 0,
                ))
            ->getQuery()
            ->getArrayResult();

        // 排序
        $count = count($provGroup);
        for ($i = 0; $i < $count; $i++) {
            for ($j = 0; $j < $count - $i - 1; $j++) {
                if (strnatcmp($provGroup[$j]['areaSpell'], $provGroup[$j + 1]['areaSpell']) == 1) {
                    $temp = $provGroup[$j];
                    $provGroup[$j] = $provGroup[$j + 1];
                    $provGroup[$j + 1] = $temp;
                }
            }
        }
        foreach ($provGroup as $key => $val) {
            $firstLetter = substr($val['areaSpell'], 0, 1);
            $provGroup[$key]['letter'] = $firstLetter;

            foreach ($cityGroup as $ck => $cv) {
                if ($val['id'] == $cv['pid']) {
                    $storeList = $mclStoresEm->findBy(array('cid' => $cv['areaId']));
                    $cv['children'] = $storeList;
                    $provGroup[$key]['city'][] = $cv;
                }
            }
        }
        foreach ($provGroup as $key => $val) {
            foreach ($val['city'] as $k => $v) {
                $cityGroupX[] = $v;
            }
        }

        return array(
            'province' => $province,
            'city' => $city,
            'provGroup' => $provGroup,
            'cityGroup' => $cityGroupX,
            'listNum' => $listNum
        );
    }
}
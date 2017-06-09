<?php
/**
 * Created by PhpStorm.
 * User: Yue
 * Date: 2017/6/8
 * Time: 17:28
 */

namespace AdminBundle\Controller;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class DashboardAjaxController
 *
 * @package AdminBundle\Controller
 * @Route("/profile/admin/ajax")
 */
class DashboardAjaxController extends Controller
{

    /**
     * Create chart
     *
     * @Route("/createChart", name="createChartAjax")
     * @param Request $request
     * @return JsonResponse
     */
    public function createChartAjax(Request $request)
    {
        $entity = $request->get("entity");
        $em = $this->getDoctrine()->getEntityManager();

        switch ($entity) {
            case "Users":
                $repo = $em->getRepository("UsersBundle:Users");
                $chartData = $this->initChart($repo); break;
            case "Brands":
                $repo = $em->getRepository("ShelfBundle:Brands");
                $chartData = $this->brandChart($repo, $em); break;
            case "Shops":
                $repo = $em->getRepository("ShelfBundle:Shops");
                $chartData = $this->initChart($repo); break;
            case "Plats":
                $repo = $em->getRepository("ShelfBundle:Plats");
                $chartData = $this->platChart($repo, $em); break;
            default:
                return new JsonResponse($this->get("getmessageservice")->getGeneralMsg("unknown")); break;
        }
        return new JsonResponse($chartData);
    }

    /**
     * Create init chart
     *
     * @param EntityRepository $repo
     * @return mixed
     */
    public function initChart($repo)
    {
        $month = ltrim(date("m", time()), "0");
        $year = date("Y", time());

        for ($i = 0; $i < 6; $i++, $month--) {
            if ($month < 10) {
                $month = "0".$month;
                if ($month <= 0) {
                    $month = 12;
                    $year--;
                }
            }
            $nextMonth = $month + 1;
            if ($nextMonth < 10) {
                $nextMonth = "0".$nextMonth;
            }

            $str = "{$year}/{$month}";
            $time = "{$year}/{$nextMonth}/1 0:0:0";

            $resultData = $repo->createQueryBuilder("i")
                ->where("i.createTime < :createTime")
                ->setParameter("createTime", $time)
                ->getQuery()
                ->getResult();

            $result[$i][0] = $str;
            $result[$i][1] = count($resultData);
        }

        return $result;
    }


    /**
     * Create brand chart
     *
     * @param EntityRepository $repo
     * @param EntityManager $em
     * @return mixed
     */
    public function brandChart($repo, $em)
    {
        $brandsData = $em->createQuery("SELECT b.brandname, count(s.id) bc FROM ShelfBundle:Shops s JOIN ShelfBundle:Brands AS b WITH s.brand = b.id WHERE s.del = :del GROUP BY s.brand ORDER BY bc DESC")
            ->setParameter("del", false)
            ->setMaxResults(6)
            ->getResult();

        foreach ($brandsData as $key => $brand) {
            $brandsResult[$key][0] = $brand["brandname"];
            $brandsResult[$key][1] = $brand["bc"];
        }

        return $brandsResult;
    }

    /**
     * Create plat chart
     *
     * @param EntityRepository $repo
     * @param EntityManager $em
     * @return mixed
     */
    public function platChart($repo, $em)
    {
        $platsData = $repo->findAll();
        foreach ($platsData as $key =>$plat) {
            $platsResult[$key]["label"] = $plat->getPlatname();
            $platsResult[$key]["data"] = count($em->getRepository("ShelfBundle:Shops")->findBy(array("plat" => $plat)));
        }

        return $platsResult;
    }
}

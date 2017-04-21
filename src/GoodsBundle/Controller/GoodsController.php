<?php
/**
 * Created by PhpStorm.
 * User: Yue
 * Date: 2017/4/21
 * Time: 15:37
 */

namespace GoodsBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class GoodsController
 *
 * @package GoodsBundle\Controller
 * @Route("/goods")
 */
class GoodsController extends Controller
{
    /**
     * Use and display model
     *
     * @Route("/displayModel/{id}/{route}", name="displayModel", defaults={"id":1, "route":"CB_TA_Model"}, requirements={"id"="\d+","route"="[A-Z]*_[A-Z]*_Model"})
     * @param Request $request
     * @param Integer $id
     * @param String $route
     */
    public function displayModelAction(Request $request, $id, $route)
    {
        $brand = explode('_', $route)[0];
        $model = explode('_', $route)[1];

        $uploadFilesEm = $this->getDoctrine()->getRepository('PublicBundle:UploadFiles');
        $filesInfo = $uploadFilesEm->findOneById($id);

        return $this->render(
            'GoodsBundle::Models/'.$route.'.html.twig',
            array(
                'id' => $id,
            )
        );
    }
}
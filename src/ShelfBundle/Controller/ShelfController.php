<?php
/**
 * Created by PhpStorm.
 * User: Yue
 * Date: 2017/4/24
 * Time: 15:13
 */

namespace ShelfBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * Class ShelfController
 *
 * @package ShelfBundle\Controller
 * @Route("/shelf")
 */
class ShelfController extends Controller
{
    /**
     * Use and display model
     *
     * @Route("/displayModel/{id}/{route}", name="displayModel", defaults={"id":"", "route":""}, requirements={"id"="\d+","route"="[A-Z]*_[A-Z]*_Model"})
     * @param Integer $id
     * @param String $route
     */
    public function displayModelAction($id, $route)
    {
        $uploadFilesEm = $this->getDoctrine()->getRepository('PublicBundle:UploadFiles');
        $shelfGoodsEm = $this->getDoctrine()->getRepository('ShelfBundle:ShelfGoods');

        $filesInfo = $uploadFilesEm->findOneById($id);
        $shelfGoodsInfo = $shelfGoodsEm->findByFile($filesInfo);
        if (!count($shelfGoodsInfo)) {
            $shelfGoodsInfo = $this->getFileDataFunc();
        }

        return $this->render(
            'ShelfBundle::Models/'.$route.'.html.twig',
            array(
                'id' => $id,
                'route' => $route,
                'data' => $shelfGoodsInfo,
            )
        );
    }

    /**
     * Shelf general Settings
     *
     * @Route("/generalSettings", name="generalSettings")
     * @return bool
     */
    public function generalSettingsAction()
    {
        return false;
    }

    public function getFileDataFunc()
    {
        return 'getFileDataFunc';
    }

    public function getUserInfoFunc($user)
    {
        $shelfUsers = $this->getDoctrine()->getRepository('ShelfBundle:ShelfUsers');
        $shelfUsers->findOneByUser($user);
    }
}
<?php
/**
 * Created by PhpStorm.
 * User: Yue
 * Date: 2017/4/24
 * Time: 15:13
 */

namespace ShelfBundle\Controller;

use Doctrine\ORM\TransactionRequiredException;
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
    private $em;

    /**
     * Use and display model
     *
     * @Route("/displayModel/{id}/{route}", name="displayModel", defaults={"id":"", "route":""}, requirements={"id"="\d+","route"="[A-Z]*_[A-Z]*_Model"})
     * @param integer $id
     * @param string $route
     */
    public function displayModelAction($id, $route)
    {
        $this->em = $this->getDoctrine()->getManager();
        $uploadFilesEm = $this->em->getRepository('PublicBundle:UploadFiles');
        $shelfGoodsEm = $this->em->getRepository('ShelfBundle:ShelfGoods');
        $shelfModelsEm = $this->em->getRepository('ShelfBundle:ShelfModels');

        $fileInfo = $uploadFilesEm->findOneById($id);
        $shelfGoodsInfo = $shelfGoodsEm->findByFile($fileInfo);
        $model = $shelfModelsEm->findOneByRoute($route)->getStyle();

        return $this->render(
            'ShelfBundle::Models/'.$route.'.html.twig',
            array(
                'id' => $id,
                'route' => $route,
                'data' => $shelfGoodsInfo,
                'model' => json_decode($model)
            )
        );
    }

    /**
     * Shelf general Settings
     *
     * @Route("/generalSettings", name="generalSettings")
     * @return boolean
     */
    public function generalSettingsAction()
    {
        return false;
    }
}

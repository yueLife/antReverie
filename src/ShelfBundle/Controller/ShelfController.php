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
use Symfony\Component\HttpFoundation\Response;

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
     * @Route("/displayModel/{id}/{route}", name="displayModel", defaults={"id": "", "route": ""}, requirements={"id" = "\d+", "route" = "[A-Z]*_[A-Z]*_Model"})
     * @param integer $id
     * @param string $route
     * @return Response
     */
    public function displayModelAction($id, $route)
    {
        $this->em = $this->getDoctrine()->getManager();
        $uploadFilesEm = $this->em->getRepository("PublicBundle:UploadFiles");
        $shelfGoodsEm = $this->em->getRepository("ShelfBundle:ShelfGoods");

        $fileInfo = $uploadFilesEm->findOneById($id);
        $shelfGoodsInfo = $shelfGoodsEm->findByFile($fileInfo);

        $option = $this->getShelfOptionFunc($route);
        return $this->render(
            "ShelfBundle::Models/".$route.".html.twig",
            array(
                "id" => $id,
                "route" => $route,
                "data" => $shelfGoodsInfo,
                "option" => $option
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


    /**
     * Get option of shelf
     *
     * @param string $route
     * @return mixed $option
     */
    public function getShelfOptionFunc($route)
    {
        $shelfUsersEm = $this->em->getRepository("ShelfBundle:ShelfUsers");
        $shelfUserInfo = $shelfUsersEm->findOneByUser($this->getUser());
        $option["personal"] = json_decode($shelfUserInfo->getPersonal());
        $option["imgList"] = json_decode($shelfUserInfo->getImgList());

        $platsEm = $this->em->getRepository("ShelfBundle:Plats");
        $platInfo = $platsEm->findOneById($shelfUserInfo->getPlat());
        $option["plat"] = json_decode($platInfo->getUrl());

        $shelfModelsEm = $this->em->getRepository("ShelfBundle:ShelfModels");
        $modelsInfo = $shelfModelsEm->findOneByRoute($route);
        $option["shelf"] = json_decode($modelsInfo->getStyle());
        $option["models"] = $shelfModelsEm->findByShop($modelsInfo->getShop());

        return $option;
    }
}

<?php
/**
 * Created by PhpStorm.
 * User: Yue
 * Date: 2017/4/24
 * Time: 15:13
 */

namespace ShelfBundle\Controller;

use Doctrine\ORM\TransactionRequiredException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

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
     * @Route("/displayModel/{id}/{route}", name="displayModel", defaults={"id": "", "route": ""}, requirements={"id" = "\d+", "route" = "[A-Z]*_[A-Z]*_Model"})
     * @param integer $id
     * @param string $route
     * @return Response
     */
    public function displayModelAction($id, $route)
    {
        $em = $this->getDoctrine()->getManager();
        $uploadFilesRepo = $em->getRepository("PublicBundle:UploadFiles");
        $goodsData = $uploadFilesRepo->findOneBy(array("id" => $id))->getGoods();

        $shelfUserData = $this->getUser()->getShelfUser();
        $option["personal"] = json_decode($shelfUserData->getPersonal());
        $option["imgList"] = json_decode($shelfUserData->getImgList());
        $option["plat"] = json_decode($shelfUserData->getPlat()->getUrl());

        $shelfModelsRepo = $em->getRepository("ShelfBundle:ShelfModels");
        $modelsData = $shelfModelsRepo->findOneBy(array("route" => $route));
        $option["shelf"] = json_decode($modelsData->getStyle());
        $option["models"] = $shelfModelsRepo->findBy(array("shop" => $modelsData->getShop()));

        return $this->render(
            "ShelfBundle::Models/".$route.".html.twig",
            array(
                "id" => $id,
                "route" => $route,
                "fileGoods" => $goodsData,
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
}

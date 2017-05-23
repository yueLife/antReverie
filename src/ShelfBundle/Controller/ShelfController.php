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
        $uploadFilesEm = $em->getRepository("PublicBundle:UploadFiles");
        $fileGoodsData = $uploadFilesEm->findOneById($id)->getGoods();

        $shelfUserData = $this->getUser()->getShelfUser();
        $option["personal"] = json_decode($shelfUserData->getPersonal());
        $option["imgList"] = json_decode($shelfUserData->getImgList());
        $option["plat"] = json_decode($shelfUserData->getPlat()->getUrl());

        $shelfModelsEm = $em->getRepository("ShelfBundle:ShelfModels");
        $modelsData = $shelfModelsEm->findOneByRoute($route);
        $option["shelf"] = json_decode($modelsData->getStyle());
        $option["models"] = $shelfModelsEm->findByShop($modelsData->getShop());

        return $this->render(
            "ShelfBundle::Models/".$route.".html.twig",
            array(
                "id" => $id,
                "route" => $route,
                "fileGoods" => $fileGoodsData,
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

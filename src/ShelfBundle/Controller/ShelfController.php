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
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
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
     * @Route("/displayModel/{id}/{route}", name="displayModel", defaults={"id":"", "route":""}, requirements={"id"="\d+","route"="[A-Z]*_[A-Z]*_Model"})
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
            'ShelfBundle::Models/'.$route.'.html.twig',
            array(
                'id' => $id,
                'route' => $route,
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
}
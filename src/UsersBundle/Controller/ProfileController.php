<?php
/**
 * Created by PhpStorm.
 * User: Yue
 * Date: 2017/5/23
 * Time: 9:40
 */

namespace UsersBundle\Controller;

use FOS\UserBundle\Controller\ProfileController as BaseController;
use FOS\UserBundle\Model\UserInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

/**
 * Class ProfileController
 *
 * @package UsersBundle\Controller
 * @Route("/profile/user")
 */
class ProfileController extends BaseController
{

    /**
     * Show the user's file
     *
     * @return mixed
     */
    public function showAction()
    {
        $doctrine = $this->container->get("doctrine");
        $user = $this->container->get('security.context')->getToken()->getUser();
        if (!is_object($user) || !$user instanceof UserInterface) {
            throw new AccessDeniedException("当前用户没有访问权限！");
        }

        if ($user->hasRole("ROLE_SUPER_ADMIN")) {
            $count["users"] = count($doctrine->getRepository("UsersBundle:Users")->findAll());
            $count["shops"] = count($doctrine->getRepository("ShelfBundle:Shops")->findAll());
            $count["plats"] = count($doctrine->getRepository("ShelfBundle:Plats")->findAll());
            $count["brands"] = count($doctrine->getRepository("ShelfBundle:Brands")->findAll());

            return $this->container->get("templating")->renderResponse("AdminBundle:Setting:dashboard.html.twig", array("count" => $count));
        }

        $shopsData = $user->getShops();
        $shop = $doctrine->getRepository("UsersBundle:Users")->findCurrentShop($user);

        return $this->container->get("templating")->renderResponse("FOSUserBundle::Profile/show.html.".$this->container->getParameter("fos_user.template.engine"), array("shops" => $shopsData, "currentShop" => $shop));
    }

    /**
     * Show the user's file
     *
     * @Route("/files", name="userFile")
     * @Template("UsersBundle::Main/userFile.html.twig")
     * @return array
     */
    public function userFileAction()
    {
        $usersRepo = $this->container->get("doctrine")->getRepository("UsersBundle:Users");
        $user = $this->container->get('security.context')->getToken()->getUser();

        $filesData = $user->getFiles();
        foreach ($filesData as $key => $file) {
            if (!$user->hasRole("ROLE_SHELF") && $file->getFileType() == "goodsFile") unset($filesData[$key]);
            if (!$user->hasRole("ROLE_WORDS") && $file->getFileType() == "detectionFile") unset($filesData[$key]);
            if (!$user->hasRole("ROLE_WORDS") && $file->getFileType() == "wordsFile") unset($filesData[$key]);
        }

        $shop = $usersRepo->findCurrentShop($user);
        $shelfModelsData = $shop->getModels();

        return array(
            "uploadFiles" => $filesData,
            "shelfModels" => $shelfModelsData
        );
    }

    /**
     * Show all files
     *
     * @Route("/allFiles", name="allFiles")
     * @Template("UsersBundle::Main/allFiles.html.twig")
     * @return array
     */
    public function allFilesAction()
    {
        $em = $this->container->get("doctrine")->getManager();
        $user = $this->container->get('security.context')->getToken()->getUser();
        $uploadFilesRepo = $em->getRepository("PublicBundle:UploadFiles");

        $uploadFilesData = $goodsFilesData = $detectionFileData = $wordsFileData = [];
        if ($user->hasRole("ROLE_SHELF")) {
            $goodsFilesData = $uploadFilesRepo->findBy(array("fileType" => "goodsFile"));
            $uploadFilesData = array_merge($uploadFilesData, $goodsFilesData);
        }
        if ($user->hasRole("ROLE_WORDS")) {
            $detectionFileData = $uploadFilesRepo->findBy(array("fileType" => "detectionFile"));
            $wordsFileData = $uploadFilesRepo->findBy(array("fileType" => "wordsFile"));
            $uploadFilesData = array_merge($uploadFilesData, $detectionFileData, $wordsFileData);
        }

        return array(
            "uploadFiles" => $uploadFilesData
        );
    }
}

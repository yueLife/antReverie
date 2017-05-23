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
 * @package UsersBundle\Controller
 * @Route("/profile")
 */
class ProfileController extends BaseController
{
    /**
     * Show the user's file
     *
     */
    public function showAction()
    {
        $em = $this->container->get("doctrine")->getManager();
        $user = $this->container->get('security.context')->getToken()->getUser();
        if (!is_object($user) || !$user instanceof UserInterface) {
            throw new AccessDeniedException("当前用户没有访问权限！");
        }

        $shops = $user->getShops();

        return $this->container->get("templating")->renderResponse("FOSUserBundle:Profile:show.html.".$this->container->getParameter("fos_user.template.engine"), array("user" => $user));
    }

    /**
     * Show the user's file
     *
     * @Route("/files", name="userFile")
     * @Template("UsersBundle::Main/userFile.html.twig")
     */
    public function userFileAction()
    {
        $em = $this->container->get("doctrine")->getManager();
        $user = $this->container->get('security.context')->getToken()->getUser();

        $filesData = $user->getFiles();
        foreach ($filesData as $key => $file) {
            if (!$user->hasRole("ROLE_GOODS") && $file->getFileType() == "goodsFile") {
                unset($filesData[$key]);
            }
            if (!$user->hasRole("ROLE_WORDS") && $file->getFileType() == "wordsFile") {
                unset($filesData[$key]);
            }
            if (!$user->hasRole("ROLE_WORDS") && $file->getFileType() == "unusedWordsFile") {
                unset($filesData[$key]);
            }
        }

        $shop = $em->createQuery("
              SELECT s FROM ShelfBundle:Shops AS s JOIN ShelfBundle:ShelfUsers AS u WITH s.plat = u.plat AND s.brand = u.brand WHERE u.user = :user"
        )->setParameter("user", $user)->getSingleResult();
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
     */
    public function allFilesAction()
    {
        $em = $this->container->get("doctrine")->getManager();
        $user = $this->container->get('security.context')->getToken()->getUser();
        $uploadFilesEm = $em->getRepository("PublicBundle:UploadFiles");

        $uploadFilesData = $goodsFilesData = $wordsFilesData = $unusedWordsFileData = [];
        if ($user->hasRole("ROLE_GOODS")) {
            $goodsFilesData = $uploadFilesEm->findByFileType("goodsFile");
            $uploadFilesData = array_merge($uploadFilesData, $goodsFilesData);
        }
        if ($user->hasRole("ROLE_WORDS")) {
            $wordsFilesData = $uploadFilesEm->findByFileType("wordsFile");
            $unusedWordsFileData = $uploadFilesEm->findByFileType("unusedWordsFile");
            $uploadFilesData = array_merge($uploadFilesData, $wordsFilesData, $unusedWordsFileData);
        }

        return array(
            "uploadFiles" => $uploadFilesData
        );
    }
}

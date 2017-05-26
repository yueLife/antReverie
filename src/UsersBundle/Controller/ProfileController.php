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
 * @Route("/profile")
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
        $usersEm = $this->container->get("doctrine")->getRepository("UsersBundle:Users");
        $user = $this->container->get('security.context')->getToken()->getUser();
        if (!is_object($user) || !$user instanceof UserInterface) {
            throw new AccessDeniedException("当前用户没有访问权限！");
        }

        $shopsData = $user->getShops();
        $shop = $usersEm->findCurrentShop($user);

        return $this->container->get("templating")->renderResponse("FOSUserBundle:Profile:show.html.".$this->container->getParameter("fos_user.template.engine"), array("shops" => $shopsData, "currentShop" => $shop));
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
        $usersEm = $this->container->get("doctrine")->getRepository("UsersBundle:Users");
        $user = $this->container->get('security.context')->getToken()->getUser();

        $filesData = $user->getFiles();
        foreach ($filesData as $key => $file) {
            if (!$user->hasRole("ROLE_SHELF") && $file->getFileType() == "goodsFile") {
                unset($filesData[$key]);
            }
            if (!$user->hasRole("ROLE_WORDS") && $file->getFileType() == "wordsFile") {
                unset($filesData[$key]);
            }
            if (!$user->hasRole("ROLE_WORDS") && $file->getFileType() == "unusedWordsFile") {
                unset($filesData[$key]);
            }
        }

        $shop = $usersEm->findCurrentShop($user);
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
        $uploadFilesEm = $em->getRepository("PublicBundle:UploadFiles");

        $uploadFilesData = $goodsFilesData = $wordsFilesData = $unusedWordsFileData = [];
        if ($user->hasRole("ROLE_SHELF")) {
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

<?php
/**
 * Created by PhpStorm.
 * User: Yue
 * Date: 2017/5/26
 * Time: 15:36
 */

namespace WordsBundle\Controller;

use Doctrine\ORM\TransactionRequiredException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class WordsAjaxController
 *
 * @package WordsBundle\Controller
 * @Route("/words/ajax")
 */
class WordsAjaxController extends Controller
{
    private $em;
    private $fileData;
    private $limit = 100;
    private $root;
    private $message = array(
        "success" => array("state" => "success", "message" =>"文件读取成功！正在跳转..."),
        "notFound" => array("state" => "error", "message" =>"文件读取失败，请重新上传！"),
        "readFailed" => array("state" => "error", "message" =>"文件读取失败，请重试！"),
        "dataWrong" => array("state" => "error", "message" =>"文件数据内容错误，请检查后重新上传！"),
        "idWrongStart" => "您的数据表中第 ",
        "idWrongEnd" => " 行数据'数字ID'可能错误，不能被识别。请重新上传！",
    );
    private $nullData = array(
        "goodsname" => "", "goodsnameSub" => "",
        "goodsBn" => "", "goodsId" => "", "imgUrl" => "",
        "introduce" => "", "detailIntroduce" => "", "unit" => "",
        "tagPrice" => "", "actPrice" => "", "couPrice" => ""
    );

    /**
     * Read unused words files data ajax
     *
     * @Route("/readWordsFileData", name="readWordsFileDataAjax")
     * @param Request $request
     * @return JsonResponse
     */
    public function readWordsFileDataAjax(Request $request)
    {
        $this->em = $this->getDoctrine()->getManager();
        $uploadFilesEm = $this->em->getRepository("PublicBundle:UploadFiles");
        $unusedWordsEm = $this->em->getRepository("WordsBundle:UnusedWords");

        $this->fileData = $uploadFilesEm->findOneById($request->get("id"));
        if ($this->fileData->getState() == "unread" && !count($this->fileData->getGoods())) {
            $this->root = $_SERVER["DOCUMENT_ROOT"]."/Uploads/files/";
            switch (pathinfo($this->fileData->getFilename(), PATHINFO_EXTENSION)) {
                case "csv":
                    $result = $this->getCsvDataFunc(); break;
                case "xlsx":
                case "xls":
                    $result = $this->getExcelDataFunc(); break;
                default:
                    return new JsonResponse($this->message["readFailed"]);
            }
            return new JsonResponse($result);
        } elseif ($this->fileData->getState() == "wrong") {
            return new JsonResponse($this->message["dataWrong"]);
        } elseif ($this->fileData->getState() == "read") {
            return new JsonResponse($this->message["success"]);
        } else {
            return new JsonResponse($this->message["readFailed"]);
        }
    }

    /**
     * Get excel file data
     *
     * @return string
     */
    public function getExcelDataFunc()
    {
        return $this->fileData->getFilename();
    }

    /**
     * Get csv file data
     *
     * @return string
     */
    public function getCsvDataFunc()
    {
        return $this->fileData->getFilename();
    }
}
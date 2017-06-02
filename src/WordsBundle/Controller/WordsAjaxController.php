<?php
/**
 * Created by PhpStorm.
 * User: Yue
 * Date: 2017/5/26
 * Time: 15:36
 */

namespace WordsBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use WordsBundle\Entity\Words;

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
        "success" => array("state" => "success", "message" =>"文件读取成功！"),
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

        $this->fileData = $uploadFilesEm->findOneById($request->get("id"));
        if ($this->fileData->getState() == "unread" && !count($this->fileData->getWords())) {
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
        $wordsEm = $this->em->getRepository("WordsBundle:Words");
        $excelFile = $this->root.$this->fileData->getFilename();
        $phpExcelObject = $this->get('phpexcel')->createPHPExcelObject($excelFile);

        $sheetCount = $phpExcelObject->getSheetCount();
        for ($i = 0,$num = 0; $i < $sheetCount; $i++) {
            $sheet = $phpExcelObject->setActiveSheetIndex($i);
            $sheetName = $sheet->getTitle();
            $cellCol = $sheet->getCellCollection();

            if (count($cellCol) > 0) {
                foreach ($cellCol as $k => $v) {
                    $inWord = $sheet->getCell($v)->getValue();
                    if (count($wordsEm->findOneByWord($inWord))) continue;

                    $newWord = new Words();
                    $newWord->setWord($inWord)->setAdder($this->getUser())->setFile($this->fileData);
                    $this->em->persist($newWord);
                    $this->em->flush();
                }
            }
            $this->fileData->setState("read");
            $this->em->flush();
        }
        return $this->message["success"];
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

    /**
     * @Route("/delRecoverWord", name="delRecoverWordAjax")
     * @param Request $request
     * @return JsonResponse
     */
    public function delRecoverWordAjax(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $wordData = $em->getRepository("WordsBundle:Words")
            ->findOneBy(array("id" => $request->get("id")));

        if (!$wordData) return new JsonResponse("error");

        if ($wordData->getDel()) {
            $wordData->setDel($operate = false)->setAdder($this->getUser())->setAddTime(new \DateTime());
        }else{
            $wordData->setDel($operate = true)->setDeleter($this->getUser())->setDelTime(new \DateTime());
        }
        $em->flush();

        return new JsonResponse(array("state" => "success", "operate" => $operate));
    }
}
<?php
/**
 * Created by PhpStorm.
 * User: Yue
 * Date: 2017/5/26
 * Time: 15:36
 */

namespace WordsBundle\Controller;

use Doctrine\ORM\EntityManager;
use PublicBundle\Entity\UploadFiles;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use WordsBundle\Entity\Words;
use WordsBundle\Entity\WordsResult;

/**
 * Class WordsAjaxController
 *
 * @package WordsBundle\Controller
 * @Route("/words/ajax")
 */
class WordsAjaxController extends Controller
{
    /**
     * Read unused words files data ajax
     *
     * @Route("/readWordsFileData", name="readWordsFileDataAjax")
     * @param Request $request
     * @return JsonResponse
     */
    public function readWordsFileDataAjax(Request $request)
    {
        $em = $this->getDoctrine()->getEntityManager();
        $uploadFilesRepo = $em->getRepository("PublicBundle:UploadFiles");

        $fileData = $uploadFilesRepo->findOneBy(array("id" => $request->get("id")));
        if ($fileData->getState() == "unread") {
            if ($fileData->getFileType() == "wordsFile" && !count($fileData->getWords()))
                return new JsonResponse($this->readWordsFileFunc($fileData, $em));
            if ($fileData->getFileType() == "detectionFile" && !count($fileData->getResult()))
                return new JsonResponse($this->readDetectionFileFunc($fileData, $em));
            return new JsonResponse($this->get("getmessageservice")->getFileMsg("readFailed"));
        } elseif ($fileData->getState() == "wrong") {
            return new JsonResponse($this->get("getmessageservice")->getFileMsg("dataWrong"));
        } elseif ($fileData->getState() == "read") {
            return new JsonResponse($this->get("getmessageservice")->getFileMsg("success"));
        } else {
            return new JsonResponse($this->get("getmessageservice")->getFileMsg("readFailed"));
        }
    }

    /**
     * Read detection files function
     * @param UploadFiles $fileData
     * @param EntityManager $em
     * @return mixed
     */
    public function readDetectionFileFunc($fileData, $em)
    {
        $wordsRepo = $em->getRepository("WordsBundle:Words");
        $wordsData = $wordsRepo->findBy(array("del" => false));

        $detectionFile = $this->get("getmessageservice")->getPathMsg("uploadFile").$fileData->getFilename();

        $phpExcelObject = $this->get("phpexcel")->createPHPExcelObject($detectionFile);

        $sheetCount = $phpExcelObject->getSheetCount();
        for ($i = 0,$num = 0; $i < $sheetCount; $i++) {
            $sheet = $phpExcelObject->setActiveSheetIndex($i);
            $sheetName = $sheet->getTitle();
            $cellCol = $sheet->getCellCollection();
            if (count($cellCol) > 0) {
                foreach ($cellCol as $k => $v) {
                    $inWord = $sheet->getCell($v)->getValue();
                    if ($inWord) {
                        $detectionData[$num]["sheet"] = $sheetName;
                        $detectionData[$num]["cell"] = $v;
                        $detectionData[$num]["content"] = $inWord;
                        $num++;
                    }
                }
            }
        }

        // 检测字段
        $num = 0;
        foreach ($detectionData as $detection) {
            foreach ($wordsData as $words) {
                if (preg_match("/".$words->getWord()."/", $detection["content"])) {
                    $newWordsResult = new WordsResult();
                    $newWordsResult->setFile($fileData)->setWord($words->getWord())->setCell($detection["cell"])->setSheet($detection["sheet"]);
                    $em->persist($newWordsResult);
                    $em->flush();
                    $num++;
                }
            }
        }

        if ($num > 0) {
            $fileData->setState("wrong");
            $em->flush();
            return $this->get("getmessageservice")->getFileMsg("checkCount", $num);
        } else {
            $fileData->setState("read");
            $em->flush();
            return $this->get("getmessageservice")->getFileMsg("checkSuccess");
        }
    }

    /**
     * @Route("/downloadMarkFile", name="downloadMarkFileAjax")
     */
    public function downloadMarkFileAjax(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $uploadFileData = $em->getRepository("PublicBundle:UploadFiles")
            ->findOneBy(array("id" => $request->get("id"), "filename" => $request->get("filename")));
        if (!$uploadFileData) return new JsonResponse("error");

        $fileUrl = $this->get("getmessageservice")->getPathMsg("uploadFile").$request->get("filename");
        $aimUrl = $this->get("getmessageservice")->getPathMsg("temp").$uploadFileData->getOldname();

        if (!$this->get("fileutilservice")->copyFile($fileUrl, $aimUrl, true))
            return new JsonResponse("error");

        $wordsResultRepo = $em->getRepository("WordsBundle:WordsResult");
        $wordsResultData = $wordsResultRepo->findBy(array("file" =>$uploadFileData));

        foreach ($wordsResultData as $result) {
            $reWordsResultData[$result->getSheet()][$result->getCell()][] = $result->getWord();
        }

        $phpExcelObject = $this->get('phpexcel')->createPHPExcelObject($aimUrl);
        foreach ($reWordsResultData as $sheetName => $cellData) {
            $sheet = $phpExcelObject->getSheetByName($sheetName);
            foreach ($cellData as $cell => $words) {
                $objRichText = new \PHPExcel_RichText();
                $minMax = 100000000;
                $cellVal = $sheet->getCell($cell)->getValue();

                while (strlen($cellVal)) {
                    $min = $minMax;
                    for ($i = 0; $i < count($words); $i++) {
                        $tmp = strpos($cellVal, $words[$i]);
                        if ($tmp <= $min && $tmp >= 0 && $tmp !== false) {
                            $min = $tmp;
                            $word = $words[$i];
                        }
                    }

                    $str = substr($cellVal, 0, $min);
                    $cellVal = substr($cellVal, $min + strlen($word));
                    if ($str) $objRichText->createTextRun($str);
                    if ($min != $minMax) {
                        $objPayable = $objRichText->createTextRun($word);
                        $objPayable->getFont()->setBold(true)->setColor(new \PHPExcel_Style_Color(\PHPExcel_Style_Color::COLOR_RED));
                    }
                }
                $sheet->getCell($cell)->setValue($objRichText);
            }
        }

        
        $this->get('phpexcel')->createWriter($phpExcelObject, 'Excel2007')->save($aimUrl);

        return new JsonResponse(array("state" => "success", "src" => "/".trim($aimUrl, $this->get("getmessageservice")->getPathMsg("root"))));
    }

    /**
     * Read words files function
     *
     * @param UploadFiles $fileData
     * @param EntityManager $em
     * @return mixed
     */
    public function readWordsFileFunc($fileData, $em)
    {
        $wordsRepo = $em->getRepository("WordsBundle:Words");
        $wordsFile = $this->get("getmessageservice")->getPathMsg("uploadFile").$fileData->getFilename();
        $phpExcelObject = $this->get("phpexcel")->createPHPExcelObject($wordsFile);

        $sheetCount = $phpExcelObject->getSheetCount();
        for ($i = 0,$num = 0; $i < $sheetCount; $i++) {
            $sheet = $phpExcelObject->setActiveSheetIndex($i);
            $sheetName = $sheet->getTitle();
            $cellCol = $sheet->getCellCollection();

            if (count($cellCol) > 0) {
                foreach ($cellCol as $k => $v) {
                    $inWord = $sheet->getCell($v)->getValue();
                    if (count($wordsRepo->findOneBy(array("word" => $inWord)))) continue;

                    $newWord = new Words();
                    $newWord->setWord($inWord)->setAdder($this->getUser())->setFile($fileData);
                    $em->persist($newWord);
                    $em->flush();
                }
            }
            $fileData->setState("read");
            $em->flush();
        }
        return $this->get("getmessageservice")->getFileMsg("success");
    }

    /**
     * @Route("/delRecoverWords", name="delRecoverWordsAjax")
     * @param Request $request
     * @return JsonResponse
     */
    public function delRecoverWordsAjax(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $wordData = $em->getRepository("WordsBundle:Words")->findOneBy(array("id" => $request->get("id")));

        if (!$wordData) return new JsonResponse("error");

        if ($wordData->getDel()) {
            $wordData->setDel($operate = false)->setAdder($this->getUser())->setAddTime(new \DateTime());
        } else {
            $wordData->setDel($operate = true)->setDeleter($this->getUser())->setDelTime(new \DateTime());
        }
        $em->flush();

        return new JsonResponse(array("state" => "success", "operate" => $operate));
    }
}

<?php
/**
 * Created by PhpStorm.
 * User: Yue
 * Date: 2017/4/28
 * Time: 10:20
 */

namespace ShelfBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use ShelfBundle\Entity\ShelfGoods;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class ShelfAjaxController
 *
 * @package ShelfBundle\Controller
 * @Route("/shelf/ajax")
 */
class ShelfAjaxController extends Controller
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
     * Get shelf file data ajax
     *
     * @Route("/getShelfFileData", name="getShelfFileDataAjax")
     * @param Request $request
     * @return JsonResponse
     */
    public function getShelfFileDataAjax(Request $request)
    {
        $this->em = $this->getDoctrine()->getManager();
        $uploadFilesEm = $this->em->getRepository("PublicBundle:UploadFiles");
        $shelfGoodsEm = $this->em->getRepository("ShelfBundle:ShelfGoods");

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
        }else{
            return new JsonResponse($this->message["readFailed"]);
        }
    }

    /**
     * Get csv file data
     *
     * @return array $result
     */
    public function getCsvDataFunc()
    {
        $file = $this->root.$this->fileData->getFilename();
        if (!is_file($file)) {
            $this->fileData->setState("wrong");
            $this->em->flush();
            return $this->message["notFound"];
        }

        $handle = fopen($file, "r");
        $filed = fgetcsv($handle);
        foreach ($filed as $key => $item) {
            switch (mb_convert_encoding($item, "utf-8", "gbk")) {
                case "宝贝名称":
                    $fields[$key] = "goodsname"; break;
                case "副名称":
                    $fields[$key] = "goodsnameSub"; break;
                case "款号":
                    $fields[$key] = "goodsBn"; break;
                case "数字ID":
                    $fields[$key] = "goodsId"; break;
                case "介绍":
                    $fields[$key] = "introduce"; break;
                case "详细信息":
                    $fields[$key] = "detailIntroduce"; break;
                case "图片链接":
                    $fields[$key] = "imgUrl"; break;
                case "吊牌价":
                    $fields[$key] = "tagPrice"; break;
                case "活动价":
                    $fields[$key] = "actPrice"; break;
                case "用券价":
                    $fields[$key] = "couPrice"; break;
                case "单位":
                    $fields[$key] = "unit"; break;
                default:
                    fclose($handle);
                    unset($fields);
                    $this->fileData->setState("wrong");
                    $this->em->flush();
                    return $this->message["dataWrong"];
            }
        }

        $tmpFile = $this->root."tmp_".date("YmdHis_").uniqid().".csv";
        @chmod($tmpFile, 0777);
        $tmpHandle = fopen($tmpFile, "a+");

        $row = 0;
        while ($line = fgetcsv($handle)) {
            $num = count($line);
            $fileData = array();
            for ($i = 0; $i < $num; $i++) {
                $fileData[$i] = mb_convert_encoding($line[$i], "utf-8", "gbk");
            }
            fputcsv($tmpHandle, $line);
            $row++;
        }

        fclose($handle);
        fclose($tmpHandle);

        // Read and insert database
        $totalPage = ceil($row / $this->limit);

        $this->em->getConnection()->beginTransaction();
        for($i = 1; $i <= $totalPage; $i++){
            $offset = ($i - 1) * $this->limit;
            $tmpData = $this->getCsvTmpDataFunc($tmpFile, $fields, $offset);

            if (isset($tmpData["state"]) && $tmpData["state"] == "error") {
                $this->em->getConnection()->rollback();
                $this->fileData->setState("wrong");
                $this->em->flush();
                return $tmpData; // $result
            }

            foreach ($tmpData as $key => $val) {
                $finalData = array_merge($this->nullData, $val);
                $goods = new ShelfGoods($this->fileData);
                $goods->setGoodsname($finalData["goodsname"])->setGoodsnameSub($finalData["goodsnameSub"])->setGoodsBn($finalData["goodsBn"])->setGoodsId($finalData["goodsId"])->setIntroduce($finalData["introduce"])->setDetailIntroduce($finalData["detailIntroduce"])->setImgUrl($finalData["imgUrl"])->setTagPrice($finalData["tagPrice"])->setActPrice($finalData["actPrice"])->setCouPrice($finalData["couPrice"])->setUnit($finalData["unit"]);
                $this->em->persist($goods);
                $this->em->flush();
            }
            $this->fileData->setState("read");
            $this->em->flush();
            $this->em->clear();
        }
        $this->em->getConnection()->commit();

        return $this->message["success"];
    }

    /**
     * Get csv tmp data
     *
     * @param string $file
     * @param array $fields
     * @param integer $offset
     * @return array $tmpData
     */
    public function getCsvTmpDataFunc($file, $fields, $offset = 0)
    {
        if (!$handle = fopen($file, "r")) return $this->message["readFailed"];

        $i = $j = 0;
        while ($i < $offset) {
            $i++;
            fgets($handle);
            if ($i == $offset) break;
        }

        $tmpData = array();
        while (($j++ < $this->limit) && !feof($handle)) {
            if (!empty($line = fgetcsv($handle))) {
                $num = count($line);
                for ($k = 0; $k < $num; $k++) {
                    $lineData[$fields[$k]] = mb_convert_encoding(trim($line[$k]), "utf-8", "gbk");
                    if ($fields[$k] == "goodsId") {
                        if (strlen($lineData[$fields[$k]]) > 15 || preg_match("/\D+/", $lineData[$fields[$k]])) {
                            $result["state"] = "error";
                            $result["message"] = $this->message["idWrongStart"].$j.$this->message["idWrongEnd"];
                            return $result;
                        }
                    }
                }
                $tmpData[] = $lineData;
            }
        }
        fclose($handle);
        unlink($file);
        return $tmpData;
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
     * Set Shelf User Data Ajax
     *
     * @Route("/setShelfUserData", name="setShelfUserDataAjax")
     * @param Request $request
     * @return JsonResponse
     */
    public function setShelfUserDataAjax(Request $request)
    {
        $this->em = $this->getDoctrine()->getManager();
        $shelfUserInfo = $this->getUser()->getShelfUser();
        $personal = json_decode($shelfUserInfo->getPersonal(), true);

        if (($cate = $request->get("cate")) == "imgTag") {
            $personal[$cate] = $request->get("value");
        } elseif ($cate == "otherColor") {
            $personal["mainTitleColor"] = $request->get("mainTitleColor");
            $personal["subTitleColor"] = $request->get("subTitleColor");
            $personal["bgColor"] = $request->get("bgColor");
            $personal["boxColor"] = $request->get("boxColor");
        } else {
            $style = "";
            if (substr($cate, 3,5) == "Title") {
                $styleArr["title"] = ($title = $request->get("title")) ? $title : "" ;
            }
            if ($styleArr["size"] = $request->get("size")) {
                $style .= "font-size:".$styleArr["size"]."px;";
            }
            if ($styleArr["family"] = $request->get("family")) {
                $style .= "font-family:".$styleArr["family"].";";
            }
            if ($styleArr["color"] = $request->get("color")) {
                $style .= "color:".$styleArr["color"].";";
            }
            if ($styleArr["line"] = $request->get("line")) {
                $style .= "text-decoration:".$styleArr["line"].";";
            }
            $styleArr["weight"] = ($weight = $request->get("weight")) ? $weight : "normal" ;
            $style .= "font-weight:".$styleArr["weight"].";";
            $styleArr["italic"] = ($italic = $request->get("italic")) ? $italic : "normal" ;
            $style .= "font-style:".$styleArr["italic"].";";

            $personal[$cate] = $style;
            $personal["style"][$cate] = $styleArr;
        }
        $shelfUserInfo->setPersonal(json_encode($personal));
        $this->em->flush();

        return new JsonResponse(array("state" => "success", "message" =>"设置成功！"));
    }

    /**
     * Set Personal Img Tag Ajax
     *
     * @Route("/setPersonalTag", name="setPersonalTagAjax")
     * @param Request $request
     * @return JsonResponse
     */
    public function setPersonalTagAjax(Request $request)
    {
        $this->em = $this->getDoctrine()->getManager();
        $shelfUsersEm = $this->em->getRepository("ShelfBundle:ShelfUsers");
        $shelfUsersInfo = $shelfUsersEm->findOneByUser($this->getUser());
        $imgList = json_decode($shelfUsersInfo->getImgList(), true);

        $imgList[$request->get("key")] = $request->get("value");
        $shelfUsersInfo->setImgList(json_encode($imgList));
        $this->em->flush();

        return new JsonResponse(array("state" => "success", "message" =>"设置成功！"));
    }
}

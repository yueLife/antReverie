<?php
/**
 * Created by PhpStorm.
 * User: Yue
 * Date: 2017/4/28
 * Time: 10:20
 */

namespace ShelfBundle\Controller;

use Doctrine\ORM\EntityManager;
use PublicBundle\Entity\UploadFiles;
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
    private $limit = 100;
    private $nullData = array(
        "goodsname" => "", "goodsnameSub" => "",
        "goodsBn" => "", "goodsId" => "", "imgUrl" => "",
        "introduce" => "", "detailIntroduce" => "", "unit" => "",
        "tagPrice" => "", "actPrice" => "", "couPrice" => ""
    );

    /**
     * Get shelf file data ajax
     *
     * @Route("/readShelfFile", name="readShelfFileAjax")
     * @param Request $request
     * @return JsonResponse
     */
    public function readShelfFileAjax(Request $request)
    {
        $em = $this->getDoctrine()->getEntityManager();
        $uploadFilesRepo = $em->getRepository("PublicBundle:UploadFiles");

        $fileData = $uploadFilesRepo->findOneBy(array("id" => $request->get("id")));
        if ($fileData->getState() == "unread" && !count($fileData->getGoods())) {
            if ($fileData->getFileType() == "goodsFile") {
                $result = $this->getCsvDataFunc($fileData, $em);
                return new JsonResponse($result);
            }
            return new JsonResponse($this->get("getmessageservice")->getFileMsg("readFailed"));
        } elseif ($fileData->getState() == "wrong") {
            return new JsonResponse($this->get("getmessageservice")->getFileMsg("dataWrong"));
        } elseif ($fileData->getState() == "read") {
            return new JsonResponse($this->get("getmessageservice")->getFileMsg("successSkip"));
        } else {
            return new JsonResponse($this->get("getmessageservice")->getFileMsg("readFailed"));
        }
    }

    /**
     * Get csv file data
     *
     * @param UploadFiles $fileData
     * @param EntityManager $em
     * @return array|mixed
     */
    public function getCsvDataFunc($fileData, $em)
    {
        $csvFile = $this->get("getmessageservice")->getPathMsg("uploadFile").$fileData->getFilename();
        if (!is_file($csvFile)) {
            $fileData->setState("wrong");
            $em->flush();
            return $this->get("getmessageservice")->getFileMsg("notFound");
        }

        $handle = fopen($csvFile, "r");
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
                    $fileData->setState("wrong");
                    $em->flush();
                    return $this->get("getmessageservice")->getFileMsg("dataWrong");
            }
        }

        $tmpFile = $this->get("getmessageservice")->getPathMsg("temp")."tmp_".date("YmdHis_").uniqid().".csv";
        @chmod($tmpFile, 0777);
        $tmpHandle = fopen($tmpFile, "a+");

        $row = 0;
        while ($line = fgetcsv($handle)) {
            fputcsv($tmpHandle, $line);
            $row++;
        }

        fclose($handle);
        fclose($tmpHandle);

        // Read and insert database
        $totalPage = ceil($row / $this->limit);

        $em->getConnection()->beginTransaction();
        for($i = 1; $i <= $totalPage; $i++){
            $offset = ($i - 1) * $this->limit;
            $tmpData = $this->getCsvTmpDataFunc($tmpFile, $fields, $offset);

            if (isset($tmpData["state"]) && $tmpData["state"] == "error") {
                $em->getConnection()->rollback();
                $fileData->setState("wrong");
                $em->flush();
                return $tmpData; // $result
            }

            foreach ($tmpData as $key => $val) {
                $finalData = array_merge($this->nullData, $val);
                $newShelfGoods = new ShelfGoods($fileData);
                $newShelfGoods->setGoodsname($finalData["goodsname"])->setGoodsnameSub($finalData["goodsnameSub"])->setGoodsBn($finalData["goodsBn"])->setGoodsId($finalData["goodsId"])->setIntroduce($finalData["introduce"])->setDetailIntroduce($finalData["detailIntroduce"])->setImgUrl($finalData["imgUrl"])->setTagPrice($finalData["tagPrice"])->setActPrice($finalData["actPrice"])->setCouPrice($finalData["couPrice"])->setUnit($finalData["unit"]);
                $em->persist($newShelfGoods);
                $em->flush();
            }
            $fileData->setState("read");
            $em->flush();
            $em->clear();
        }
        $em->getConnection()->commit();

        return $this->get("getmessageservice")->getFileMsg("successSkip");
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
        if (!$handle = fopen($file, "r")) return $this->get("getmessageservice")->getFileMsg("readFailed");

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
                            return $this->get("getmessageservice")->getFileMsg("idWrong", $j);
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
     * Set Shelf User Data Ajax
     *
     * @Route("/setShelfUserData", name="setShelfUserDataAjax")
     * @param Request $request
     * @return JsonResponse
     */
    public function setShelfUserDataAjax(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $shelfUserData = $this->getUser()->getShelfUser();
        $personal = json_decode($shelfUserData->getPersonal(), true);

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
        $shelfUserData->setPersonal(json_encode($personal));
        $em->flush();

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
        $em = $this->getDoctrine()->getManager();
        $shelfUsersRepo = $em->getRepository("ShelfBundle:ShelfUsers");
        $shelfUsersData = $shelfUsersRepo->findOneBy(array("user" => $this->getUser()));
        $imgList = json_decode($shelfUsersData->getImgList(), true);

        $imgList[$request->get("key")] = $request->get("value");
        $shelfUsersData->setImgList(json_encode($imgList));
        $em->flush();

        return new JsonResponse(array("state" => "success", "message" =>"设置成功！"));
    }
}

<?php
/**
 * Created by PhpStorm.
 * User: Yue
 * Date: 2017/4/28
 * Time: 10:20
 */

namespace ShelfBundle\Controller;

use ShelfBundle\Entity\ShelfGoods;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class ShelfAjaxController
 *
 * @package ShelfBundle\Controller
 * @Route("/ajax/shelf")
 */
class ShelfAjaxController extends Controller
{
    private $em;
    private $limit = 100;
    private $root;
    private $message = array(
        'success' => array('state' => 'success', 'message' =>'文件读取成功！正在跳转...'),
        'readFailed' => array('state' => 'error', 'message' =>'文件读取失败，请重试或重新上传！'),
        'dataWrong' => array('state' => 'error', 'message' =>'文件数据内容错误，请重新上传！'),
        'idWrongStart' => '您的数据表中第 ',
        'idWrongEnd' => ' 行数据"数字ID"可能错误，不能被识别。请重新上传！',
    );

    /**
     * Get shelf file data ajax
     *
     * @Route("/getShelfFileData", name="getShelfFileDataAjax")
     * @param Request $request
     */
    public function getShelfFileDataAjax(Request $request)
    {
        $this->em = $this->getDoctrine()->getManager();
        $uploadFilesEm = $this->em->getRepository('PublicBundle:UploadFiles');
        $shelfGoodsEm = $this->em->getRepository('ShelfBundle:ShelfGoods');

        $fileInfo = $uploadFilesEm->findOneById($request->get('id'));
        if ($fileInfo->getState() == 'unread' && !count($shelfGoodsEm->findByFile($fileInfo))) {
            $this->root = $_SERVER['DOCUMENT_ROOT']."/Uploads/files/";
            switch (pathinfo($fileInfo->getFilename(), PATHINFO_EXTENSION)) {
                case 'csv':
                    $result = $this->getCsvDataFunc($fileInfo); break;
                case 'xlsx':
                case 'xls':
                    $result = $this->getExcelDataFunc($fileInfo); break;
                default:
                    return new JsonResponse($this->message['readFailed']);
            }
            return new JsonResponse($result);
        } elseif ($fileInfo->getState() == 'wrong') {
            return new JsonResponse($this->message['dataWrong']);
        } elseif ($fileInfo->getState() == 'read') {
            return new JsonResponse($this->message['success']);
        }else{
            return new JsonResponse($this->message['readFailed']);
        }
    }

    /**
     * Get csv file data
     *
     * @param String $fileInfo
     * @return Array $result
     */
    public function getCsvDataFunc($fileInfo)
    {
        $tmpFileData = $this->createCsvTmpFunc($this->root.$fileInfo->getFilename());
        if (isset($tmpFileData['state']) && $tmpFileData['state'] == 'error') {
            $fileInfo->setState('wrong');
            $this->em->flush();
            return $tmpFileData; // $result
        }

        $totalPage = ceil($tmpFileData['row'] / $this->limit);
        $nullData = array(
            'goodsname' => '',
            'goodsnameSub' => '',
            'goodsBn' => '',
            'goodsId' => '',
            'introduce' => '',
            'detailIntroduce' => '',
            'imgUrl' => '',
            'tagPrice' => '',
            'actPrice' => '',
            'couPrice' => '',
            'unit' => ''
        );

        $this->em->getConnection()->beginTransaction();
        for($i = 1; $i <= $totalPage; $i++){
            $offset = ($i - 1) * $this->limit;
            $tmpData = $this->getCsvTmpDataFunc($tmpFileData['tmp'], $tmpFileData['fields'], $offset);

            if (isset($tmpData['state']) && $tmpData['state'] == 'error') {
                $this->em->getConnection()->rollback();
                $fileInfo->setState('wrong');
                $this->em->flush();
                return $tmpData; // $result
            }

            foreach ($tmpData as $key => $val) {
                $finalData = array_merge($nullData, $val);
                $goods = new ShelfGoods($fileInfo);
                $goods->setGoodsname($finalData['goodsname'])->setGoodsnameSub($finalData['goodsnameSub'])->setGoodsBn($finalData['goodsBn'])->setGoodsId($finalData['goodsId'])->setIntroduce($finalData['introduce'])->setDetailIntroduce($finalData['detailIntroduce'])->setImgUrl($finalData['imgUrl'])->setTagPrice($finalData['tagPrice'])->setActPrice($finalData['actPrice'])->setCouPrice($finalData['couPrice'])->setUnit($finalData['unit']);
                $this->em->persist($goods);
                $this->em->flush();
            }
            $fileInfo->setState('read');
            $this->em->flush();
            $this->em->clear();
        }
        $this->em->getConnection()->commit();

        return $this->message['success'];
    }
    
    /**
     * Create csv temp File and convert encoding utf-8
     *
     * @param String $file
     * @return Array $tmpFileData
     */
    public function createCsvTmpFunc($file)
    {
        // Get first line of file and translate
        $handle = fopen($file, 'r');
        $filed = fgetcsv($handle);
        foreach ($filed as $key => $item) {
            switch (mb_convert_encoding($item, 'utf-8', 'gbk')) {
                case '宝贝名称':
                    $tmpFileData['fields'][$key] = 'goodsname'; break;
                case '副名称':
                    $tmpFileData['fields'][$key] = 'goodsnameSub'; break;
                case '款号':
                    $tmpFileData['fields'][$key] = 'goodsBn'; break;
                case '数字ID':
                    $tmpFileData['fields'][$key] = 'goodsId'; break;
                case '介绍':
                    $tmpFileData['fields'][$key] = 'introduce'; break;
                case '详细信息':
                    $tmpFileData['fields'][$key] = 'detailIntroduce'; break;
                case '图片链接':
                    $tmpFileData['fields'][$key] = 'imgUrl'; break;
                case '吊牌价':
                    $tmpFileData['fields'][$key] = 'tagPrice'; break;
                case '活动价':
                    $tmpFileData['fields'][$key] = 'actPrice'; break;
                case '用券价':
                    $tmpFileData['fields'][$key] = 'couPrice'; break;
                case '单位':
                    $tmpFileData['fields'][$key] = 'unit'; break;
                default:
                    fclose($handle);
                    return $this->message['dataWrong'];
            }
        }

        $tmpFileData['tmp'] = $this->root.'tmp_'.date('YmdHis_').uniqid().'.csv';
        @chmod($tmpFileData['tmp'], 0777);
        $tmpHandle = fopen($tmpFileData['tmp'], 'a+');

        $tmpFileData['row'] = 0;
        while ($line = fgetcsv($handle)) {
            $num = count($line);
            $fileData = array();
            for ($i = 0; $i < $num; $i++) {
                $fileData[$i] = mb_convert_encoding($line[$i], 'utf-8', 'gbk');
            }
            fputcsv($tmpHandle, $line);
            $tmpFileData['row']++;
        }

        fclose($handle);
        fclose($tmpHandle);

        return $tmpFileData;
    }

    /**
     * Get csv tmp data
     *
     * @param String $file
     * @param Array $fields
     * @param Integer $offset
     * @return array
     */
    public function getCsvTmpDataFunc($file, $fields, $offset = 0)
    {
        if (!$handle = fopen($file, 'r')) return $this->message['readFailed'];

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
                    $lineData[$fields[$k]] = mb_convert_encoding(trim($line[$k]), 'utf-8', 'gbk');
                    if ($fields[$k] == 'goodsId') {
                        if (strlen($lineData[$fields[$k]]) > 10 || preg_match("/\D+/", $lineData[$fields[$k]])) {
                            $result['state'] = 'error';
                            $result['message'] = $this->message['idWrongStart'].$j.$this->message['idWrongEnd'];
                            return $result;
                        }
                    }
                }
                $tmpData[] = $lineData;
            }
        }
        fclose($handle);
        return $tmpData;
    }

    /**
     * Get excel file data
     *
     * @param String $fileInfo
     * @return string
     */
    public function getExcelDataFunc($fileInfo)
    {
        return $fileInfo->getFilename();
    }
}
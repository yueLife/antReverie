<?php
/**
 * Created by PhpStorm.
 * User: Yue
 * Date: 2017/4/28
 * Time: 10:20
 */

namespace ShelfBundle\Controller;

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
    private $root;

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
        $shelfGoodsInfo = $shelfGoodsEm->findByFile($fileInfo);
        if (!count($shelfGoodsInfo)) {
            $this->root = $_SERVER['DOCUMENT_ROOT']."/Uploads/files/";
            switch (pathinfo($fileInfo->getFilename(), PATHINFO_EXTENSION)) {
                case 'csv':
                    $shelfGoodsInfo = $this->getCsvFileDataFunc($fileInfo); break;
                case 'xlsx':
                case 'xls':
                    $shelfGoodsInfo = $this->getExcelFileDataFunc($fileInfo); break;
                default:
                    return new JsonResponse(array('status'=>'error','message'=>'文件获取失败！'));
            }
        }

        return new JsonResponse($data);
    }

    /**
     * Get csv file data
     *
     * @param String $fileInfo
     * @return string
     * @throws TransactionRequiredException
     */
    public function getCsvFileDataFunc($fileInfo)
    {
        $csvFile = $this->root.$fileInfo->getFilename();
        $tmpFileData = $this->createCsvTmpFileFunc($csvFile);
        if (isset($tmpFileData['status']) && $tmpFileData['status'] == 'error') {
            return 1;
        }

        return $tmpFileData;
    }

    /**
     * Create Csv temp File and convert encoding utf-8
     *
     * @param String $file
     * @return Array $data
     */
    public function createCsvTmpFileFunc($file)
    {
        // Get first line of file and translate
        $handle = fopen($file, 'r');
        $title = fgetcsv($handle);
        foreach ($title as $key => $item) {
            switch (mb_convert_encoding($item, 'utf-8', 'gbk')) {
                case '宝贝名称1':
                    $data['title'][$key] = 'goodsname'; break;
                case '副名称':
                    $data['title'][$key] = 'goodsnameSub'; break;
                case '款号':
                    $data['title'][$key] = 'goodsBn'; break;
                case '数字ID':
                    $data['title'][$key] = 'goodsId'; break;
                case '介绍':
                    $data['title'][$key] = 'introduce'; break;
                case '详细信息':
                    $data['title'][$key] = 'detailIntrod'; break;
                case '图片链接':
                    $data['title'][$key] = 'imgUrl'; break;
                case '吊牌价':
                    $data['title'][$key] = 'tagPrice'; break;
                case '活动价':
                    $data['title'][$key] = 'actPrice'; break;
                case '用券价':
                    $data['title'][$key] = 'couPrice'; break;
                case '单位':
                    $data['title'][$key] = 'unit'; break;
                default:
                    fclose($handle);
                    $data['status'] = 'error';
                    $data['message'] = '文件数据获取失败，请重新检查文件内容或联系管理员！';
                    return $data;
            }
        }

        $data['tmp'] = $this->root.'tmp'.time().uniqid().'.csv';
        @chmod($data['tmp'], 0777);
        $tmpHandle = fopen($data['tmp'], 'a+');

        $data['row'] = 0;
        while ($line = fgetcsv($handle)) {
            $num = count($line);
            $fileData = array();
            for ($i = 0; $i < $num; $i++) {
                $fileData[$i] = mb_convert_encoding($line[$i], 'utf-8', 'gbk');
            }
            fputcsv($tmpHandle, $line);
            $data['row']++;
        }

        fclose($handle);
        fclose($tmpHandle);

        return $data;
    }

    public function getExcelFileDataFunc($fileInfo)
    {
        return 'xls';
    }

    public function getUserInfoFunc($user)
    {
        $shelfUsers = $this->getDoctrine()->getRepository('ShelfBundle:ShelfUsers');
        $shelfUsers->findOneByUser($user);
    }
}
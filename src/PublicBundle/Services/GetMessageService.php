<?php
/**
 * Created by PhpStorm.
 * User: Yue
 * Date: 2017/6/5
 * Time: 14:37
 */

namespace PublicBundle\Services;

/**
 * Class GetMessageService
 * @package PublicBundle\Services
 */
class GetMessageService
{
    public function getFileMsg($title, $num = -1)
    {
        $message = array(
            "success" => array("state" => "success", "message" =>"文件读取成功！"),
            "successSkip" => array("state" => "success", "message" =>"文件读取成功！正在跳转..."),
            "notFound" => array("state" => "error", "message" =>"文件读取失败，请重新上传！"),
            "readFailed" => array("state" => "error", "message" =>"文件读取失败，请重试！"),
            "dataWrong" => array("state" => "error", "message" =>"文件数据内容错误，请检查后重新上传！"),
            "idWrong" => array("state" => "error", "message" =>"您的数据表中第 {$num} 行数据'数字ID'可能错误，不能被识别。请重新上传！"),
            "checkSuccess" => array("state" => "success", "message" =>"您的文件中没有禁词错误！"),
            "checkCount" => array("state" => "error", "message" =>"您的检测文件中有 {$num} 个禁词错误，请重新检查！")
        );

        return $message[$title];
    }

    public function getPathMsg($path)
    {
        $message = array(
            "root" => $_SERVER["DOCUMENT_ROOT"],
            "uploadFile" => $_SERVER["DOCUMENT_ROOT"]."/Uploads/files/",
            "avatars" => $_SERVER["DOCUMENT_ROOT"]."/Uploads/avatars/",
            "temp" => $_SERVER["DOCUMENT_ROOT"]."/Uploads/temp/"
        );

        return $message[$path];
    }

    public function getGeneralMsg($title)
    {
        $message = array(
            "unknown" => array("state" => "error", "message" => "未知错误，请重试!")
        );

        return $message[$title];
    }
}
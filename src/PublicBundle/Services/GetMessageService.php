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
            "unknown" => array("state" => "error", "message" => "未知错误，请重试!"),
            "setSuccess" => array("state" => "success", "message" => "设置成功！"),
            "setFailed" => array("state" => "error", "message" => "设置失败！"),
            "addSuccess" => array("state" => "success", "message" => "添加成功！"),
            "addError" => array("state" => "error", "message" => "添加失败！")
        );

        return $message[$title];
    }

    public function getUserMsg($title)
    {
        $message = array(
            "uniqueUsername" => array("state" => "error", "message" => "该用户名已存在，请重新输入!"),
            "uniqueEmail" => array("state" => "error", "message" => "该邮箱已存在，请重新输入!"),
            "editSuccess" => array("state" => "success", "message" => "该用户信息修改成功!"),
            "addSuccess" => array("state" => "success", "message" => "新用户添加成功!"),
            "passwordError" => array("state" => "error", "message" => "两次密码不一致，请重新输入!"),
            "oldPasswordError" => array("state" => "error", "message" => "旧密码输入错误！请重新输入！"),
            "passwordLengthError" => array("state" => "error", "message" => "密码长度应为6-12位！请重新输入！"),
            "passwordTypeError" => array("state" => "error", "message" => "密码长度必须为数字、字母或下划线!")
        );

        return $message[$title];
    }

    public function getShelfMsg($title)
    {
        $message = array(
            "uniqueBrandname" => array("state" => "error", "message" => "该品牌已存在，请重新输入!"),
            "uniquePlatname" => array("state" => "error", "message" => "该平台已存在，请重新输入!"),
            "uniqueshopname" => array("state" => "error", "message" => "该店铺已存在，请重新输入!"),
            "addSuccess" => array("state" => "success", "message" => "添加成功!"),
            "editSuccess" => array("state" => "success", "message" => "修改成功!"),
        );

        return $message[$title];
    }
}
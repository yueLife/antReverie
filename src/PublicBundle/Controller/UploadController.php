<?php

namespace PublicBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use PublicBundle\Vendor\UploadHandler;

class UploadController extends UploadHandler
{
    protected $maxFile = 5 * 1024 * 1024;

    // 24
    protected $error_messages = array(
        1 => '上传的文件超过了php.ini中的upload_max_filesize指令',
        2 => '上传的文件超过HTML格式中指定的MAX_FILE_SIZE指令',
        3 => '上传的文件仅部分上传',
        4 => '没有上传文件',
        6 => '缺少一个临时文件夹',
        7 => '无法将文件写入磁盘',
        8 => '一个PHP扩展名停止文件上传',
        'post_max_size' => '上传的文件超过了php.ini中的post_max_size伪指令',
        'max_file_size' => '单文件不允许超过5MB',
        'min_file_size' => '单文件太小',
        'accept_file_types' => '该文件格式不允许上传',
        'max_number_of_files' => '单次最大上传文件个数为10个',
        'max_width' => '图像超出最大宽度',
        'min_width' => '图像需要最小宽度',
        'max_height' => '图像超过最大高度',
        'min_height' => '图像需要最小高度',
        'abort' => '文件上传中止',
        'image_resize' => '无法调整图像大小',
    );

    // 46
    public function __construct()
    {
        parent::__construct();
    }

    // 1128
    protected function body($str)
    {
        return false;
    }

    // 1180
    protected function get_file_type($file_path)
    {
        switch (strtolower(pathinfo($file_path, PATHINFO_EXTENSION))) {
            case 'jpeg':
            case 'jpg':
                return 'image/jpeg';
            case 'png':
                return 'image/png';
            case 'gif':
                return 'image/gif';
            case 'xlsx':
                return 'application/xlsx';
            case 'xls':
                return 'application/xls';
            case 'csv':
                return 'application/csv';
            default:
                return '';
        }
    }
}

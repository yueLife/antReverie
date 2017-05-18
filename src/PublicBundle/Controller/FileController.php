<?php
/**
 * Created by PhpStorm.
 * User: Yue
 * Date: 2017/4/19
 * Time: 11:26
 */

namespace PublicBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use PublicBundle\Entity\UploadFiles;

/**
 * Class FileController
 *
 * @package PublicBundle\Controller
 * @Route("/files")
 */
class FileController extends Controller
{
    /**
     * File upload function
     *
     * @Route("/upload", name="uploadFile")
     * @param Request $request
     * @return JsonResponse
     */
    public function uploadFileAction(Request $request)
    {
        $uploadData =  $this->get("UploadHandlerService");
        $data = $uploadData->response["files"][0];

        if (empty($data->error)) {
            $fileUtil = $this->get("FileUtilService");
            $root = $_SERVER["DOCUMENT_ROOT"]."/Uploads/";
            $filename = "file_".uniqid().".".pathinfo($data->name, PATHINFO_EXTENSION);

            if ($fileUtil->moveFile($root."data/".$data->name, $root."files/".$filename)) {
                $em = $this->getDoctrine()->getManager();
                $newUploadFiles = new UploadFiles($this->getUser());
                $newUploadFiles->setFilename($filename)->setOldname($data->name)->setFileType($request->get("fileType"));

                $em->persist($newUploadFiles);
                $em->flush();

                $uploadData->response["files"][0]->id = $newUploadFiles->getId();
                $uploadData->response["files"][0]->filename = $newUploadFiles->getFilename();
                $uploadData->response["files"][0]->uploadTime = $newUploadFiles->getUploadTime();
                $uploadData->response["files"][0]->fileType = $newUploadFiles->getFileType();
            }
        }
        return new JsonResponse($uploadData->response);
    }

    /**
     * Delete file method
     *
     * @Route("/deleteFile", name="deleteFileAjax")
     * @param Request $request
     * @return JsonResponse
     */
    public function deleteFileAjax(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $uploadFilesInfo = $em->getRepository("PublicBundle:UploadFiles")
            ->findOneBy(array("id" => $request->get("id"), "filename" => $request->get("filename")));
        if (!$uploadFilesInfo) {
            return new JsonResponse("error");
        }

        $uploadFilesInfo->setDel(true);
        $em->flush();

        return new JsonResponse("success");
    }

    /**
     * Download file method
     *
     * @Route("/downloadFile", name="downloadFileAjax")
     * @param Request $request
     * @return JsonResponse
     */
    public function downloadFileAjax(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $uploadFilesInfo = $em->getRepository("PublicBundle:UploadFiles")
            ->findOneBy(array("id" => $request->get("id"), "filename" => $request->get("filename")));
        if (!$uploadFilesInfo) {
            return new JsonResponse("error");
        }
        $fileUtil = $this->get("FileUtilService");
        $root = $_SERVER["DOCUMENT_ROOT"]."/Uploads/";
        $filename = $uploadFilesInfo->getOldname();
        if (!$fileUtil->copyFile($root."files/".$request->get("filename"), $root."temp/".$filename, true)) {
            return new JsonResponse("error");
        }

        return new JsonResponse(array("state" => "success", "src" => "/Uploads/temp/".$uploadFilesInfo->getOldname()));
    }
}

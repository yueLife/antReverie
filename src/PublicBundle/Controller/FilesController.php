<?php
/**
 * Created by PhpStorm.
 * User: Yue
 * Date: 2017/4/19
 * Time: 11:26
 */

namespace PublicBundle\Controller;

use PublicBundle\Entity\UploadFiles;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class FileController
 *
 * @package PublicBundle\Controller
 * @Route("/profile/files")
 */
class FilesController extends Controller
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
        $uploadData =  $this->get("uploadhandlerservice");
        $data = $uploadData->response["files"][0];

        if (empty($data->error)) {
            $root = $_SERVER["DOCUMENT_ROOT"]."/Uploads/";
            $filename = "file_".uniqid().".".pathinfo($data->name, PATHINFO_EXTENSION);

            if ($this->get("fileutilservice")->moveFile($root."data/".$data->name, $root."files/".$filename)) {
                $em = $this->getDoctrine()->getManager();
                $newUploadFile = new UploadFiles($this->getUser());
                $newUploadFile->setFilename($filename)->setOldname($data->name)->setFileType($request->get("fileType"));

                $em->persist($newUploadFile);
                $em->flush();

                $uploadData->response["files"][0]->id = $newUploadFile->getId();
                $uploadData->response["files"][0]->filename = $newUploadFile->getFilename();
                $uploadData->response["files"][0]->uploadTime = $newUploadFile->getUploadTime()->format("Y-m-d H:i:s");
                $uploadData->response["files"][0]->fileType = $newUploadFile->getFileType();
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
        $uploadFileRepo = $em->getRepository("PublicBundle:UploadFiles")
            ->findOneBy(array("id" => $request->get("id"), "filename" => $request->get("filename")));
        if (!$uploadFileRepo) return new JsonResponse("error");

        $uploadFileRepo->setDel(true);
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
        $uploadFileRepo = $em->getRepository("PublicBundle:UploadFiles")
            ->findOneBy(array("id" => $request->get("id"), "filename" => $request->get("filename")));
        if (!$uploadFileRepo) return new JsonResponse("error");

        $filUrl = $this->get("getmessageservice")->getPathMsg("uploadFile").$request->get("filename");
        $aimUrl = $this->get("getmessageservice")->getPathMsg("temp").$uploadFileRepo->getOldname();
        if (!$this->get("fileutilservice")->copyFile($filUrl, $aimUrl, true)) return new JsonResponse("error");

        return new JsonResponse(array("state" => "success", "src" => "/Uploads/temp/".$uploadFileRepo->getOldname()));
    }
}

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
                $uploadData->response["files"][0]->fileType = $newUploadFiles->getFileType();
                $uploadData->response["files"][0]->uploadTime = $newUploadFiles->getUploadTime();
            }
        }
        return new JsonResponse($uploadData->response);
    }

    /**
     * Deleting file method
     *
     * @Route("/delete", name="deleteFile")
     * @param Request $request
     * @return JsonResponse
     */
    public function deleteFileAction(Request $request)
    {
        $data["id"] = $request->get("id");
        $data["filename"] = $request->get("filename");
        return new JsonResponse($data);
    }
}

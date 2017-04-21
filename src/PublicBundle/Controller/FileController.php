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
 * @package PublicBundle\Controller
 */
class FileController extends Controller
{
    /**
     * Deleting file method
     *
     * @Route("/deleteFile", name="deleteFile")
     */
    public function deleteFileAction(Request $request)
    {
        $data['id'] = $request->get('id');
        $data['filename'] = $request->get('filename');
        return new JsonResponse($data);
    }

    /**
     * File upload function
     *
     * @Route("/uploadFile", name="uploadFile")
     */
    public function uploadFileAction(Request $request)
    {
        $uploadData =  $this->get('UploadHandlerService');
        $data = $uploadData->response['files'][0];

        if (empty($data->error)) {
            $fileUtil = $this->get('FileUtilService');
            $root = $_SERVER['DOCUMENT_ROOT'].'/Uploads/';
            $filename = 'file_'.uniqid().'.'.pathinfo($data->name, PATHINFO_EXTENSION);

            if ($fileUtil->moveFile($root.'data/'.$data->name, $root.'files/'.$filename)) {
                $em = $this->getDoctrine()->getManager();
                $newUploadFiles = new UploadFiles($this->getUser());
                $newUploadFiles->setFilename($filename)->setOldname ($data->name)->setFileType($request->get('fileType'));

                $em->persist($newUploadFiles);
                $em->flush();
            }
        }

        return new JsonResponse($uploadData->response);
    }
}
<?php
/**
 * Created by PhpStorm.
 * User: Yue
 * Date: 2017/3/24
 * Time: 14:22
 */

namespace PublicBundle\Controller;

use GoodsBundle\Entity\GoodsFiles;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use PublicBundle\Entity\UploadFiles;
use WordsBundle\Entity\WordsFiles;

class HomeController extends Controller
{
    /**
     * @Route("/", name="home")
     * @Template("PublicBundle:Home:index.html.twig")
     */
    public function indexAction(Request $request)
    {
        return array('name' => 'name');
    }

    /**
     * File upload function
     *
     * @Route("/upload", name="fileUpload")
     */
    public function fileUploadAction()
    {
        $uploadData =  $this->get('UploadHandlerService');
        $data = $uploadData->response['files'][0];

        if (empty($data->error)) {
            $fileUtil = $this->get('FileUtilService');
            $root = $_SERVER['DOCUMENT_ROOT'].'/Uploads/';
            $filename = 'file_'.uniqid().'.'.pathinfo($data->name, PATHINFO_EXTENSION);

            if ($fileUtil->moveFile($root.'data/'.$data->name, $root.'files/'.$filename)) {
                if ($this->getUser()->hasRole('ROLE_GOODS')) {
                    $this->addDataToDatabase(new GoodsFiles($this->getUser()), $filename, $data->name);
                }
                if ($this->getUser()->hasRole('ROLE_WORDS')) {
                    $this->addDataToDatabase(new WordsFiles($this->getUser()), $filename, $data->name);
                }
                $this->addDataToDatabase(new UploadFiles($this->getUser()), $filename, $data->name);
            }
        }

        return new JsonResponse($uploadData->response);
    }

    /**
     * Add data to the database
     *
     * @param object $en
     * @param string $new
     * @param string $old
     */
    private function addDataToDatabase($en, $new, $old)
    {
        $em = $this->getDoctrine()->getManager();
        $en->setFilename($new)->setOldname($old);
        $em->persist($en);
        $em->flush();
    }
}

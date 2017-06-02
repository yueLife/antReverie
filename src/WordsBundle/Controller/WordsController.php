<?php
/**
 * Created by PhpStorm.
 * User: Yue
 * Date: 2017/5/26
 * Time: 15:36
 */

namespace WordsBundle\Controller;

use Doctrine\ORM\TransactionRequiredException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class WordsController
 *
 * @package WordsBundle\Controller
 * @Route("/words")
 */
class WordsController extends Controller
{
    /**
     * Show all words
     *
     * @Route("", name="words")
     * @Template("WordsBundle::Main/words.html.twig")
     * @return array
     */
    public function wordsAction()
    {
        $em = $this->getDoctrine()->getEntityManager();
        $wordsEm = $em->getRepository("WordsBundle:Words");
        $wordsData = $wordsEm->findAll();
        return array("words" => $wordsData);
    }
}
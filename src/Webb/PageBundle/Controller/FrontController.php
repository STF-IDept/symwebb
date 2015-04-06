<?php

namespace Webb\PageBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * @Route("/")
 */
class FrontController extends Controller
{

    /**
     * @Route("/", name="webb_page_homepage")
     * @Template("WebbPageBundle:Front:index.html.twig")
     */
    public function indexAction()
    {
        $qb = $this->getDoctrine()->getRepository('WebbNewsBundle:Article')->getArticlesByTags('front_page');

        $qb ->orderBy('a.date', 'DESC')
            ->setMaxResults(2);

        $articles = $qb->getQuery()->getResult();

        return array('ship' => 'asimov', 'articles' => $articles);

    }
}

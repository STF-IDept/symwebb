<?php

namespace Webb\PageBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class FrontController extends Controller
{
    public function indexAction()
    {
        //$tag = $this->getDoctrine()->getRepository('WebbNewsBundle:Tag')->findByName("Front Page");
        //$article = $this->getDoctrine()->getRepository('WebbNewsBundle:Article')->findOneBy(array('tags' => $tag));
        //var_dump($article);
        //$article = $repository->findByTags(1);
        //$articles = $this->getDoctrine()->getRepository('WebbNewsBundle:Article')->findByTags(array('Front Page'));
        //return $this->render('WebbPageBundle:Front:index.html.twig', array('ship' => 'asimov', 'articles' => $article));

        /*$tags = array('Front Page');
        $tagids = array();

        foreach($tags as $tag) {
            $tagids[] = $this->getDoctrine()->getRepository('WebbNewsBundle:Tag')->findOneByName($tag)->getId();
        }

        $qb = $this->getEntityManager()->getConfiguration()->getRepository->createQueryBuilder('t');
        $qb -> join('t.name', 'f')
            -> where($qb->expr()->in('f.id', $tagids));*/

        $qb = $this->getDoctrine()->getRepository('WebbNewsBundle:Article')->getArticlesByTags('Front Page');

        $qb ->orderBy('a.date', 'DESC')
            ->setMaxResults(2);

        $articles = $qb->getQuery()->getResult();

        return $this->render('WebbPageBundle:Front:index.html.twig', array('ship' => 'asimov', 'articles' => $articles));

    }
}

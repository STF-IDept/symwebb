<?php
/**
 * src/Webb/NewsBundle/Controller/ArticleController.php
 */

namespace Webb\NewsBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Webb\NewsBundle\Entity\Article;
use Webb\NewsBundle\Form\Type\ArticleType;
use \DateTime;

class ArticleController extends Controller
{
    public function showAction($id)
    {
        $article = $this->getDoctrine()->getRepository('WebbNewsBundle:Article')->find($id);

        if (!$article) {
            throw $this->createNotFoundException(
                'No article found for id '.$id
            );
        }

        return $this->render('WebbNewsBundle:Article:show.html.twig', array('article' => $article));
    }

    public function createAction(Request $request)
    {
        $article = new Article();

        $article->setUser($this->getUser());

        //Do this post form submission, prior to validation
        $time = new DateTime;
        $time->setTimestamp(time());
        $article->setDate($time);

        $form = $this->createForm(new ArticleType(), $article);
        if ($request->getMethod() == 'POST') {
            $form->bind($request);

            if ($form->isValid()) {
                // perform some action, such as saving the task to the database
                $em = $this->getDoctrine()->getManager();
                $em->persist($article);
                $em->flush();

                return $this->redirect($this->generateUrl('webb_news_article_view', array('id' => $article->getID())));
            }
        }

        return $this->render('WebbNewsBundle:Article:create.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    public function editAction($id, Request $request)
    {
        $article = $this->getDoctrine()->getRepository('WebbNewsBundle:Article')->find($id);
        $form = $this->createForm(new ArticleType(), $article);

        if (!$article) {
            throw $this->createNotFoundException(
                'No article found for id '.$id
            );
        }

        if ($request->getMethod() == 'POST') {
            $form->bind($request);

            if ($form->isValid()) {
                // perform some action, such as saving the task to the database
                $em = $this->getDoctrine()->getManager();
                $em->persist($article);
                $em->flush();

                return $this->redirect($this->generateUrl('webb_news_article_view', array('id' => $article->getID())));
            }
        }

        return $this->render('WebbNewsBundle:Article:edit.html.twig', array(
            'form' => $form->createView(),
            'id' => $id,
        ));
    }

    public function listAction()
    {
        $qb = $this->getDoctrine()->getRepository('WebbNewsBundle:Article')->getArticlesByTags('front_page');

        $qb ->orderBy('a.date', 'DESC')
            ->setMaxResults(2);

        $articles = $qb->getQuery()->getResult();

        return $this->render('WebbNewsBundle:Article:list.html.twig', array('articles' => $articles));
    }
}

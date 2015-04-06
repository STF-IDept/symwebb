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
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;


/**
 * @Route("/news")
 */
class ArticleController extends Controller
{
    /**
     * @Route("/{id}", name="webb_news_article_view", requirements={"id" = "\d+"})
     * @Template("WebbNewsBundle:Article:show.html.twig")
     */
    public function showAction($id)
    {
        $article = $this->getDoctrine()->getRepository('WebbNewsBundle:Article')->find($id);

        if (!$article) {
            throw $this->createNotFoundException(
                'No article found for id '.$id
            );
        }

        return array('article' => $article);
    }

    /**
     * @Route("/create", name="webb_news_article_create")
     * @Security("has_role('ROLE_NEWS_CREATE')")
     * @Template("WebbNewsBundle:Article:create.html.twig")
     */
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

        return array('form' => $form->createView());
    }

    /**
     * @Route("/{id}/edit", name="webb_news_article_edit", requirements={"id" = "\d+"})
     * @Security("has_role('ROLE_NEWS_EDIT')")
     * @Template("WebbNewsBundle:Article:edit.html.twig")
     */
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

        return array('form' => $form->createView(), 'id' => $id,);
    }

    /**
     * @Route("/list", name="webb_news_article_list")
     * @Template("WebbNewsBundle:Article:list.html.twig")
     */
    public function listAction()
    {
        $qb = $this->getDoctrine()->getRepository('WebbNewsBundle:Article')->getArticlesByTags('front_page');

        $qb ->orderBy('a.date', 'DESC')
            ->setMaxResults(2);

        $articles = $qb->getQuery()->getResult();

        return array('articles' => $articles);
    }
}

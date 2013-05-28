<?php

namespace Webb\PageBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class FrontController extends Controller
{
    public function indexAction()
    {
        $note = $this->getDoctrine()->getRepository('WebbPostBundle:Note')->findBy(array());
        return $this->render('WebbPageBundle:Front:index.html.twig', array('ship' => 'asimov'));
    }
}

<?php

namespace Webb\NewsBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('WebbNewsBundle:Default:index.html.twig', array('name' => $name));
    }
}

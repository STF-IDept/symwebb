<?php

namespace Webb\MotdBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('WebbMotdBundle:Default:index.html.twig', array('name' => $name));
    }
}

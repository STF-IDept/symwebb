<?php

namespace Webb\FileBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('WebbFileBundle:User:show.html.twig', array('name' => $name));
    }
}

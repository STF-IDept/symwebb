<?php

namespace Webb\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($id)
    {
        return $this->render('WebbUserBundle:Default:index.html.twig', array('name' => $id));
    }
}

<?php

namespace Webb\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($username)
    {
        return $this->render('WebbUserBundle:Default:index.html.twig', array('name' => $username));
    }
}

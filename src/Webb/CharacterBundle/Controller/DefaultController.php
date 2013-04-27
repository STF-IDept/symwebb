<?php

namespace Webb\CharacterBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('WebbCharacterBundle:Default:index.html.twig', array('name' => $name));
    }
}

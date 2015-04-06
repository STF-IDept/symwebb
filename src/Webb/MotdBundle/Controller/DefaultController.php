<?php

namespace Webb\MotdBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class DefaultController extends Controller
{
    /**
     * @Template("WebbMotdBundle:User:show.html.twig")
     */
    public function indexAction($name)
    {
        return array('name' => $name);
    }
}

<?php

namespace Webb\FileBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * @Security("has_role('ROLE_USER')")
 **/
class DefaultController extends Controller
{
    /**
     * @Template("WWebbFileBundle:User:show.html.twig")
     */
    public function indexAction($name)
    {
        return array('name' => $name);
    }
}

<?php

namespace Webb\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        $message = \Swift_Message::newInstance()
            ->setSubject('Hello Email')
            ->setFrom('stfcoder@gmail.com')
            ->setTo('jack@jackdipper.com')
            ->setBody('Hello')
        ;
        $this->get('mailer')->send($message);

        return $this->render('WebbUserBundle:Default:index.html.twig', array('name' => $name));
    }
}

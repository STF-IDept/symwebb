<?php

namespace Webb\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class ProfileController extends Controller
{
    public function showAction($id)
    {
        $user = $this->getDoctrine()->getRepository('WebbUserBundle:User')->find($id);
        return $this->render('WebbUserBundle:Profile:show.html.twig', array('profile' => $user));
    }
}

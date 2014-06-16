<?php

use Webb\FileBundle\Entity\Image;
use Symfony\Component\HttpFoundation\Request;

class ImageController extends Controller {
    public function uploadAction(Request $request)
    {
        $image = new Image();
        $form = $this->createFormBuilder($image)
            ->add('name')
            ->add('file')
            ->getForm();

        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            $image->upload();
            $em->persist($image);
            $em->flush();

            //return $this->redirect($this->generateUrl(...));
        }

        return array('form' => $form->createView());
    }
}
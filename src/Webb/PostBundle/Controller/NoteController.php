<?php
/**
 * src/Webb/PostBundle/Controller/NoteController.php
 */

namespace Webb\PostBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Webb\PostBundle\Entity\Note;
use Webb\PostBundle\Form\Type\NoteType;
use \DateTime;

class NoteController extends Controller
{
    public function showAction($id)
    {
        //$securityContext = new SecurityContext();
        //$persona = new Persona($id);

        $note = $this->getDoctrine()->getRepository('WebbPostBundle:Note')->find($id);

        if (!$note) {
            throw $this->createNotFoundException(
                'No note found for id '.$id
            );
        }
        //return $this->render('WebbCharacterBundle:Persona:index.html.twig', array('name' => $user));
        return $this->render('WebbPostBundle:Note:show.html.twig', array('note' => $note));
    }

    public function createAction($fleet, $ship, $parent_id,  Request $request)
    {
        $note = new Note();

        $note->setUser($this->getUser());
        $note->setShip($this->getDoctrine()->getRepository('WebbShipBundle:Ship')->findOneBy(array('shortname' => $ship)));

            if(!is_null($parent_id)) {
            $parent = $this->getDoctrine()->getRepository('WebbPostBundle:Note')->find($parent_id);

            $note->setContent("> ".str_replace("\n", "\n> ", trim($parent->getContent()))."\n\n");
            $note->setActivity($parent->getActivity());
            $note->setParent($parent);
        }

        //Do this post form submission, prior to validation
        $time = new DateTime;
        $time->setTimestamp(time());
        $note->setDate($time);
        //$note->setPersona($note->getAssignment()->getPersona());
        $form = $this->createForm(new NoteType(), $note);
        if ($request->getMethod() == 'POST') {
            $form->bind($request);

            if ($form->isValid()) {
                // perform some action, such as saving the task to the database
                $em = $this->getDoctrine()->getManager();
                $em->persist($note);
                $em->flush();

                return $this->redirect($this->generateUrl('webb_post_note_view', array('fleet' => $fleet, 'ship' => $ship, 'id' => $note->getID())));
            }
        }

        return $this->render('WebbPostBundle:Note:create.html.twig', array(
            'fleet' => $fleet,
            'ship' => $ship,
            'form' => $form->createView(),
        ));



    }

    public function editAction($fleet, $ship, $id, Request $request)
    {
        $note = $this->getDoctrine()->getRepository('WebbPostBundle:Note')->find($id);
        $form = $this->createForm(new NoteType(), $note);

        if (!$note) {
            throw $this->createNotFoundException(
                'No character found for id '.$id
            );
        }

        if ($request->getMethod() == 'POST') {
            $form->bind($request);

            if ($form->isValid()) {
                // perform some action, such as saving the task to the database
                $em = $this->getDoctrine()->getManager();
                $em->persist($note);
                $em->flush();

                return $this->redirect($this->generateUrl('webb_post_note_view', array('fleet' => $fleet, 'ship' => $ship, 'id' => $note->getID())));
            }
        }

        return $this->render('WebbPostBundle:Note:edit.html.twig', array(
            'form' => $form->createView(),
            'fleet' => $fleet,
            'ship' => $ship,
            'id' => $id,
        ));
    }
}

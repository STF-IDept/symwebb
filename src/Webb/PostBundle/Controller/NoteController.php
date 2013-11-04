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
    public function showAction($ship, $id)
    {

        $user = $this->getUser();

        if($user) {
            $userid = $user->getId();
        }
        else {
            $userid = 0;
        }

        $note = $this->getDoctrine()->getManager()->createQueryBuilder()
            ->select('n, l, p, a, q, r, s')
            ->from('WebbPostBundle:Note', 'n')
            ->where('n.id = :id AND (u.id IS NULL OR u.id = :user_id)')
            ->setParameter('id', $id)
            ->setParameter('user_id', $userid)
            ->innerJoin('n.location', 'l')
            ->innerJoin('n.persona', 'p')
            ->innerJoin('n.assignment', 'a')
            ->innerJoin('a.position', 'q')
            ->innerJoin('q.position', 'r')
            ->innerJoin('p.rank', 's')
            ->leftJoin('n.readers', 'u')
            ->getQuery()->getOneOrNullResult();

        if (!$note) {
            throw $this->createNotFoundException(
                'No note found for id '.$id
            );
        }

        $shortname = $ship;
        unset($ship);

        $ship = $this->getDoctrine()->getManager()->createQueryBuilder()
            ->select('s, f')
            ->from('WebbShipBundle:Ship', 's')
            ->where('s.shortname = :shortname')->setParameter('shortname', $shortname)
            ->innerJoin('s.fleet', 'f')
            ->getQuery()->getOneOrNullResult();

        if (!$ship) {
            throw $this->createNotFoundException(
                'Ship not found'
            );
        }
        /*elseif ($ship->getFleet()->getId() != $fleet) {
            return $this->redirect($this->generateUrl('webb_ship_ship_view', array('fleet' => $ship->getFleet()->getId(), 'shortname' => $shortname)));
        }*/

        if($userid && !count($note->getReaders())) {
            $note->addReader($user);
            // Save to the database
            $em = $this->getDoctrine()->getManager();
            $em->persist($note);
            $em->flush();
        }

        $previous = $this->getDoctrine()->getManager()->createQueryBuilder()
            ->select('n')
            ->from('WebbPostBundle:Note', 'n')
            ->where('n.ship = :ship_id AND n.id < :note_id')->setParameter('ship_id', $ship->getId())
            ->setParameter('note_id', $id)
            ->orderBy('n.id', 'DESC')
            ->setMaxResults(1)
            ->getQuery()->getOneOrNullResult();

        $nextcron = $this->getDoctrine()->getManager()->createQueryBuilder()
            ->select('n')
            ->from('WebbPostBundle:Note', 'n')
            ->where('n.ship = :ship_id AND n.id > :note_id')->setParameter('ship_id', $ship->getId())
            ->setParameter('note_id', $id)
            ->orderBy('n.id', 'ASC')
            ->setMaxResults(1)
            ->getQuery()->getOneOrNullResult();

        $nextloc = $this->getDoctrine()->getManager()->createQueryBuilder()
            ->select('n')
            ->from('WebbPostBundle:Note', 'n')
            ->where('n.ship = :ship_id AND n.id > :note_id AND n.location = :note_location')
            ->setParameter('ship_id', $ship->getId())
            ->setParameter('note_id', $id)
            ->setParameter('note_location', $note->getLocation())
            ->orderBy('n.id', 'ASC')
            ->setMaxResults(1)
            ->getQuery()->getOneOrNullResult();

        return $this->render('WebbPostBundle:Note:show.html.twig', array('note' => $note, 'ship' => $ship, 'previous' => $previous, 'nextcron' => $nextcron, 'nextloc' => $nextloc));
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

    public function recentPostsAction($ship, $note) {

        $user = $this->getUser();

        if($user) {
            $userid = $user->getId();
        }
        else {
            $userid = 0;
        }

        $notequery = $this->getDoctrine()->getManager()->createQueryBuilder()
            ->select('n, l, p, a, q, r, s, t, u')
            ->from('WebbPostBundle:Note', 'n')
            ->where('n.ship = :ship_id AND (u.id IS NULL OR u.id = :user_id)')
            ->setParameter('ship_id', $ship->getId())
            ->setParameter('user_id', $userid)
            ->innerJoin('n.location', 'l')
            ->innerJoin('n.persona', 'p')
            ->innerJoin('n.assignment', 'a')
            ->innerJoin('a.position', 'q')
            ->innerJoin('q.position', 'r')
            ->innerJoin('p.rank', 's')
            ->leftJoin('n.child', 't')
            ->leftJoin('n.readers', 'u');

        $notes = $notequery->getQuery()->execute();

        $arr = array();


        foreach($notes as $item) {
            if(!$this->searchArray($item->getId(), $arr)) {
                $arr = array_merge($arr, $this->getChildPost($item));
            }
        }


        return $this->render('WebbPostBundle:Note:recentposts.html.twig', array('notes' => $arr, 'ship' => $ship, 'note' => $note));
    }

    private function getChildPost($note, $indent = 0) {

        $arr = array();
        $arr[] = array('note' => $note, 'id' => $note->getId(), 'indent' => $indent);

        for($i=0; $i < $note->getChild()->count(); $i++) {
            $arr = array_merge($arr, $this->getChildPost($note->getChild()->get($i), $indent + 1));
        }

        return $arr;

    }

    private function searchArray($id, $array){
        foreach($array as $val) {
            if($val['id'] === $id) {
                return true;
            }
        }
        return false;
    }
}

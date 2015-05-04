<?php
/**
 * src/Webb/PostBundle/Controller/NoteController.php
 */

namespace Webb\PostBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Webb\PostBundle\Entity\Note;
use Webb\PostBundle\Form\Type\NoteType;
use \DateTime;
use Webb\UserBundle\Entity\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * @Route("/{fleet}/{ship}/notes", requirements={"fleet" = "^stf\d+|^acad|^command"})
 */
class NoteController extends Controller
{
    /**
     * @Route("/{id}", name="webb_post_note_view", requirements={"id" = "\d+"})
     * @Template("WebbPostBundle:Note:show.html.twig")
     */
    public function showAction($ship, $id, Request $request)
    {
        $userid = ($user = $this->getUser()) ? $user->getId() : 0;

        $note = $this->getDoctrine()->getManager()->createQueryBuilder()
            ->select('n, l, p, a, q, r, s')
            ->from('WebbPostBundle:Note', 'n')
            ->where('n.id = :id')->setParameter('id', $id)
            ->innerJoin('n.location', 'l')
            ->innerJoin('n.persona', 'p')
            ->innerJoin('n.assignment', 'a')
            ->innerJoin('a.position', 'q')
            ->innerJoin('q.parent', 'r')
            ->innerJoin('p.rank', 's')
            ->getQuery()->getOneOrNullResult();

        if (!$note) {
            throw $this->createNotFoundException('No note found for id '.$id);
        }

        $ship = $this->getShipByShortName($ship);

        if($userid) {
            $this->saveHistory($note, $user);
        }

        $links = $this->getLinks($note, $ship->getId(), $userid);

        $form = $this->dateSelect($request);

        return array('note' => $note, 'ship' => $ship, 'links' => $links, 'form' => $form->createView(), 'dates' => $form->getData());
    }

    /**
     * @Route("/", name="webb_post_note_list")
     * @Template("WebbPostBundle:Note:list.html.twig")
     */
    public function listAction($ship, Request $request)
    {

        $ship = $this->getShipByShortName($ship);

        $form = $this->dateSelect($request);

        $dates = $form->getData();

        return array('note' => 0, 'ship' => $ship, 'form' => $form->createView(), 'dates' => $dates);
    }

    /**
     * @Route("/create", name="webb_post_note_create", defaults={"parent_id" = 0})
     * @Route("/{parent_id}/reply", name="webb_post_note_reply", requirements={"parent_id" = "\d+"})
     * @Security("has_role('ROLE_POST_CREATE')")
     * @Template("WebbPostBundle:Note:create.html.twig")
     */
    public function createAction($fleet, $ship, $parent_id = 0,  Request $request)
    {
        // Declare new note
        $note = new Note();
        // Look up ship based on shortname passed by routing
        $ship = $this->getDoctrine()->getRepository('WebbShipBundle:Ship')->findOneBy(array('shortname' => $ship));

        // Declare hidden variables that the user does not have access to
        $note->setUser($this->getUser());
        $note->setShip($ship);
        $note->setPublished(true);

        // Prepare the note, and return some variables for the template
        if($request->getMethod() != 'POST') {
            $template_var = $this->prepareNote($note, $parent_id, $ship->getID());
        }

        // Generate the form
        $form = $this->createForm(new NoteType(), $note, array('ship' => $ship->getId(), 'user' => $this->getUser()));

        // Handle form submissions
        if ($request->getMethod() == 'POST') {

            if($parent_id) {

                // Get the  parent node
                $parent = $this->getDoctrine()->getRepository('WebbPostBundle:Note')->find($parent_id);

                // And specify parent and thread
                $note->setParent($parent);
                $note->setThread($parent->getThread());
            }

            $form->bind($request);

            $note->setPersona($note->getAssignment()->getPersona());

            // Set time for post
            $time = new DateTime;
            $time->setTimestamp(time());
            $note->setDate($time);

            if ($form->isValid()) {
                // Save the note to the database
                $em = $this->getDoctrine()->getManager();
                $em->persist($note);
                $em->flush();

                // And redirect to the note
                return $this->redirect($this->generateUrl('webb_post_note_view', array('fleet' => $fleet, 'ship' => $ship->getShortName(), 'id' => $note->getID())));
            }
        }

        // If we didn't get redirected, time to display the posting form
        return array(
            'fleet' => $fleet,
            'ship' => $note->getShip(),
            'form' => $form->createView(),
            'meta' => $template_var,
            'id' => false,
        );

    }

    /**
     * @Route("{id}", name="webb_post_note_edit", requirements={"id" = "\d+"})
     * @Security("has_role('ROLE_POST_EDIT')")
     * @Template("WebbPostBundle:Post:edit.html.twig")
     */
    public function editAction($fleet, $ship, $id, Request $request)
    {
        $note = $this->getDoctrine()->getRepository('WebbPostBundle:Note')->find($id);
        $form = $this->createForm(new NoteType(), $note);

        if (!$note) {
            throw $this->createNotFoundException(
                'No post found for id '.$id
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

        return array(
            'form' => $form->createView(),
            'fleet' => $fleet,
            'ship' => $note->getShip(),
            'selected' => $note,
            'method' => "webb_post_note_edit",
            'parent' => $note->getParent(),
            'id' => $note->getId(),
        );
    }

    /**
     * @Template("WebbPostBundle:Note:recentposts.html.twig")
     */
    public function recentPostsAction($ship, $noteid, $dates) {

        $userid = ($user = $this->getUser()) ? $user->getId() : 0;

        if(!is_object($ship)) {
            $ship = $this->getShipByShortName($ship);
        }

        // Make sure the Start and End times are set to 00:00:00 and 23:59:59 respectively.
        $dates['start']->setTime(0,0,0);
        $dates['end']->setTime(23,59,59);

        $notes = $this->getDoctrine()->getManager()->createQueryBuilder()
            ->select('note, location, persona, assignment, position, parent, rank, log, child, ship, fleet, log2')
            ->from('WebbPostBundle:Note', 'note')
            ->where('note.ship = :ship_id')
            ->andWhere('note.date >= :start')
            ->andWhere('note.date <= :end')
            ->setParameter('ship_id', $ship->getId())
            ->setParameter('start', $dates['start'])
            ->setParameter('end', $dates['end'])
            ->innerJoin('note.location', 'location')
            ->innerJoin('note.persona', 'persona')
            ->innerJoin('note.assignment', 'assignment')
            ->innerJoin('assignment.position', 'position')
            ->innerJoin('position.parent', 'parent')
            ->innerJoin('persona.rank', 'rank')
            ->innerJoin('note.ship', 'ship')
            ->innerJoin('ship.fleet', 'fleet')
            ->leftJoin('note.log', 'log')
            ->leftJoin('note.child', 'child')
            ->leftJoin('child.log', 'log2') //No idea why adding this drops the SQL hits.
            ->orderBy('note.date')
            ->getQuery()->execute();


        $temp = array();
        $arr = array();
        $ids = array();

        // Pop all of the retrieved notes into an array
        foreach($notes as $item) {
            $temp[$item->getId()] = $item;
            $ids[] = $item->getId();
        }

        $history = $this->getHistory($ids, $userid);

        // For each note, process the child posts, and pop into a new array to build our post tree
        foreach($temp as &$item) {
            $arr = array_merge($arr, $this->prepareRecentPosts($temp, $item, null, $noteid, $history, $userid));
        }

        return array('notes' => $arr, 'ship' => $ship, 'history' => $history);
    }

    private function getHistory($ids, $userid) {
        // Get the history list
        $history_arr = array();
        if($userid) {
            $history_bld = $this->getDoctrine()->getManager()->createQueryBuilder()
                ->select('h')
                ->from('WebbPostBundle:History', 'h');
            $history_arr = $history_bld->where($history_bld->expr()->in('h.note', ':my_array'))
                ->setParameter('my_array', $ids)
                ->andWhere('h.user = :user_id')
                ->setParameter('user_id', $userid)
                ->getQuery()->execute();
        }

        $history = array();
        foreach($history_arr as $item) {
            $history[$item->getNote()->getId()] = $item->getNote()->getId();
        }

        return $history;

    }

    private function prepareRecentPosts(&$notes, Note $note, $indent = 0, &$current_id = 0, &$history, $userid) {

        $arr = array();

        // Work out if there is any special styling for the note preview
        $style = ($current_id == $note->getId()) ? "current" : "";

        $tags = array();

        // And any tags that there are, including the new tag. The order of the array is the order in which they display.
        if(!isset($history[$note->getId()]) && $current_id != $note->getId() && $userid) {
            $tags['new'] = "New";
        }
        if($note->getLog()->getLog()) {
            $tags['log'] = $note->getAssignment()->getPosition()->getShortName()." Log";
        }

        // Store the note that is being processed at the top of the array
        $arr[] = array(
            'note' => $note,
            'id' => $note->getId(),
            'indent' => $indent,
            'style' => $style,
            'tags' => $tags,
        );

        // And check for children
        foreach($note->getChild() as $child) {
            // $notes[$child->getId()] will give us the child note, which we will then put through this recusive function.
            if(isset($notes[$child->getId()])) {
                $arr = array_merge($arr, $this->prepareRecentPosts($notes, $notes[$child->getId()], $indent + 1, $current_id, $history, $userid));
            }
        }

        unset($notes[$note->getId()]);

        return $arr;

    }

    /**
     * @Route("/list/rss/{format}", name="webb_post_note_rss", defaults={"format" = "raw"})
     */
    public function feedAction($ship, $format)
    {
        $ship = $this->getShipByShortName($ship);

        $articles = $this->getDoctrine()->getManager()->createQueryBuilder()
            ->select('note, location, persona, assignment, position, rank, ship, fleet')
            ->from('WebbPostBundle:Note', 'note')
            ->where('note.ship = :ship_id')
            ->setParameter('ship_id', $ship->getId())
            ->andWhere('note.date >= :start')
            ->setParameter('start', new DateTime('-1 week'))
            ->innerJoin('note.location', 'location')
            ->innerJoin('note.persona', 'persona')
            ->innerJoin('note.assignment', 'assignment')
            ->innerJoin('assignment.position', 'position')
            ->innerJoin('persona.rank', 'rank')
            ->innerJoin('note.ship', 'ship')
            ->innerJoin('ship.fleet', 'fleet')
            ->orderBy('note.date')
            ->getQuery()->execute();

        foreach($articles as $article) {
          if($format == "raw") {
            $article->setContent(nl2br($article->getContent()));
          }
          else {
            $article->setContent($this->container->get('markdown.parser')->transformMarkdown($article->getContent()));
          }
        }

        $feed = $this->get('eko_feed.feed.manager')->get('article');
        $feed->addFromArray($articles);

        return new Response($feed->render('rss')); // or 'atom'
    }

    private function getShipByShortName($shortname) {

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

        return $ship;
    }

    private function dateSelect(Request $request)
    {
        $session = $request->getSession();
        $start = is_null($session->get('start')) ? new DateTime("-1 week") : $session->get('start');
        $end = is_null($session->get('end')) ? new DateTime() : $session->get('end');

        $default = array('start' => $start, 'end' => $end);
        $form = $this->createFormBuilder($default)
            ->add('start', 'date', array(
                'widget' => 'single_text',
                'html5' => false,
                'format' => 'd MMM y'
            ))
            ->add('end', 'date', array(
                'widget' => 'single_text',
                'html5' => false,
                'format' => 'd MMM y'
            ))
            ->add('View', 'submit')
            ->getForm();

        $form->handleRequest($request);

        $data = $form->getData();

        $session->set('start', $data['start']);
        $session->set('end', $data['end']);

        return $form;
    }

    private function getLinks(Note $note, $shipid, $userid) {

        $result['previouscron'] = $this->getDoctrine()->getManager()->createQueryBuilder()
            ->select('n')
            ->from('WebbPostBundle:Note', 'n')
            ->where('n.ship = :ship_id AND n.id < :note_id')->setParameter('ship_id', $shipid)
            ->setParameter('note_id', $note->getId())
            ->orderBy('n.id', 'DESC')
            ->setMaxResults(1)
            ->getQuery()->getOneOrNullResult();

        $result['nextcron'] = $this->getDoctrine()->getManager()->createQueryBuilder()
            ->select('n')
            ->from('WebbPostBundle:Note', 'n')
            ->where('n.ship = :ship_id AND n.id > :note_id')->setParameter('ship_id', $shipid)
            ->setParameter('note_id', $note->getId())
            ->orderBy('n.id', 'ASC')
            ->setMaxResults(1)
            ->getQuery()->getOneOrNullResult();

        $result['nextnew'] = $this->getDoctrine()->getManager()->createQueryBuilder()
            ->select('n', 'h')
            ->from('WebbPostBundle:Note', 'n')
            ->innerJoin('n.history', 'h')
            ->innerJOin('h.user', 'u')
            ->where('n.ship = :ship_id')->setParameter('ship_id', $shipid)
            ->andWhere('n.id > :note_id')->setParameter('note_id', $note->getId())
            ->andWhere('u.id = :user_id')->setParameter('user_id', $userid)
            ->orderBy('n.id', 'ASC')
            ->setMaxResults(1)
            ->getQuery()->getOneOrNullResult();

        $result['nextthread'] = $this->getDoctrine()->getManager()->createQueryBuilder()
            ->select('n')
            ->from('WebbPostBundle:Note', 'n')
            ->where('n.ship = :ship_id AND n.id > :note_id AND n.thread = :note_thread')
            ->setParameter('ship_id', $shipid)
            ->setParameter('note_id', $note->getId())
            ->setParameter('note_thread', $note->getThread())
            ->orderBy('n.id', 'ASC')
            ->setMaxResults(1)
            ->getQuery()->getOneOrNullResult();

        return $result;
    }

    private function saveHistory(Note $note, User $user) {

        $new = true;

        foreach($note->getHistory() as $item) {
            if($item->getUser()->getId()) {
                $new = false;
                break;
            }
        }

        if($new) {
            $history = new \Webb\PostBundle\Entity\History();
            $history->setUser($user)->setNote($note);
            $em = $this->getDoctrine()->getManager();
            $em->persist($history);
            $em->flush();
        }

    }

    private function prepareNote(Note &$note, $parent_id, $ship_id) {

        if($parent_id) {
            // Get the actual parent node
            $parent = $this->getDoctrine()->getRepository('WebbPostBundle:Note')->find($parent_id);

            // Replace all new lines with > to indicate quoted text
            $content = "> Posted by {$parent->getAssignment()} played by {$parent->getUser()}\n";
            $content .= "> Posted on {$parent->getDate()->format('l j M Y')} at {$parent->getDate()->format('g:ia T')} \n>\n";
            $content .= "> ".str_replace("\n", "\n> ", trim($parent->getContent()))."\n\n";
            $note->setContent($content);

            // Pick up the previous activity, and the location
            $note->setActivity($parent->getActivity());
            $note->setLocation($parent->getLocation());

            // We know there were past posts.  Check to see if any of them were authored by the user.  If so, take the last one and use that character.
            $assignment_query = $this->getDoctrine()->getManager()->createQueryBuilder()
                ->select('note')
                ->from('WebbPostBundle:Note', 'note')
                ->where('note.ship = :ship_id')->andWhere('note.user = :user_id')->andWhere('note.thread = :note_thread')
                ->setParameter('ship_id', $ship_id)
                ->setParameter('user_id', $this->getUser()->getID())
                ->setParameter('note_thread', $parent->getThread())
                ->orderBy('note.id', 'DESC')
                ->setMaxResults(1)
                ->getQuery()->getOneOrNullResult();

            if(!is_null($assignment_query)) {
                $note->setAssignment($assignment_query->getAssignment());
            }

            $return['method'] = "webb_post_note_reply";
            $return['parent'] = $parent_id;
            $return['parent_loc'] = $parent->getLocation();
            $return['parent_act'] = $parent->getActivity();
        }
        else {
            $return['method'] = "webb_post_note_create";
            $return['parent'] = $parent_id;
        }

        // If the note assignment is still null, use the last one used by the user.
        if(is_null($note->getAssignment())) {
            $userid = ($user = $this->getUser()) ? $user->getId() : 0;

            $assignment_query = $this->getDoctrine()->getManager()->createQueryBuilder()
                ->select('note')
                ->from('WebbPostBundle:Note', 'note')
                ->where('note.ship = :ship_id')->andWhere('note.user = :user_id')
                ->setParameter('ship_id', $ship_id)
                ->setParameter('user_id', $userid)
                ->orderBy('note.id', 'DESC')
                ->setMaxResults(1)
                ->getQuery()->getOneOrNullResult();

            if(!is_null($assignment_query)) {
                $note->setAssignment($assignment_query->getAssignment());
            }
        }

        return $return;

    }

}

<?php

namespace Webb\ShipBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Webb\ShipBundle\Form\Type\PositionType;
use Webb\ShipBundle\Form\Type\ShipType;
use Symfony\Component\HttpFoundation\Request;
use Webb\MotdBundle\Entity\Box;
use Webb\ShipBundle\Entity\PositionCollection;
use Doctrine\ORM\Query;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;


/**
 * @Route("/")
 */
class ShipController extends Controller
{
    /**
     * @Route("{fleet}/{ship}", name="webb_ship_ship_view", requirements={"fleet" = "^stf\d+|^acad|^command"})
     */
    public function showAction($fleet, $ship)
    {

        $this->loadShip($ship, $fleet);

        /* Join the positions, assignments and personae */
        $query = $this->getDoctrine()->getManager()->createQueryBuilder()
            ->select('b, p, q, a, r, s, u')
            ->from('WebbMotdBundle:Box', 'b')
            ->where('b.ship = :ship_id')->setParameter('ship_id', $ship->getId())
            ->leftJoin('b.position', 'p')
            ->leftJoin('p.parent', 'q')
            ->leftJoin('p.assignment', 'a', 'WITH', 'a.active = 1')
	        ->leftJoin('a.persona', 'r')
	        ->leftJoin('r.rank', 's')
	        ->leftJoin('r.user', 'u')
	        ->orderBy('b.boxorder', 'asc');

        $boxes = $query->getQuery()->execute();

        array_walk($boxes, array($this, 'prepareShowResult'));

        return $this->render('WebbMotdBundle:'.$ship->getStyle()->getShortname().':ship.html.twig', array(
            'ship' => $ship,
            'boxes' => $boxes,
            'fleet' => $fleet,
        ));
    }

    /**
     * @Route("ship/create", name="webb_ship_ship_create")
     * @Security("has_role('ROLE_SHIP_CREATE')")
     * @Template("WebbShipBundle:Ship:create.html.twig")
     */
    public function createAction(Request $request)
    {
        $ship = new Ship();
        $form = $this->createForm(new ShipType(), $ship);

        if ($request->getMethod() == 'POST') {
            $form->bind($request);

            if ($form->isValid()) {
                // perform some action, such as saving the task to the database
                $em = $this->getDoctrine()->getManager();
                $em->persist($ship);
                $em->flush();

                return $this->redirect($this->generateUrl('webb_ship_ship_view', array('fleet' => $ship->getFleet()->getId(), 'ship' => $ship->getShortname())));
            }
        }

        return array(
            'form' => $form->createView(),
        );

    }

    /**
     * @Route("{fleet}/{ship}/edit", name="webb_ship_ship_edit", requirements={"fleet" = "^stf\d+|^acad|^command"})
     * @Security("has_role('ROLE_SHIP_EDIT')")
     * @Template("WebbShipBundle:Ship:edit.html.twig")
     */
    public function editAction($fleet, $ship, Request $request)
    {
        $this->loadShip($ship, $fleet);

        $form = $this->createForm(new ShipType(), $ship);

        if ($request->getMethod() == 'POST') {
            $form->bind($request);

            if ($form->isValid()) {
                // perform some action, such as saving the task to the database
                $em = $this->getDoctrine()->getManager();
                $em->persist($ship);
                $em->flush();

                return $this->redirect($this->generateUrl('webb_ship_ship_view', array('fleet' => $ship->getFleet()->getId(), 'shortname' => $ship->getShortname())));
            }
        }

        return array(
            'form' => $form->createView(),
            'fleet' => $fleet,
            'ship' => $ship,
        );
    }

    /**
     * @Route("{fleet}/{ship}/positions", name="webb_ship_positions", requirements={"fleet" = "^stf\d+|^acad|^command"})
     * @Template("WebbShipBundle:Ship:positions.html.twig")
     */
    public function positionsAction($fleet, $ship, Request $request)
    {
        $this->loadShip($ship, $fleet);

        $positions = $this->getDoctrine()->getManager()->createQueryBuilder()
            ->select('p, q, a, r, s, u, t, f')
            ->from('WebbShipBundle:Position', 'p')
            ->where('p.ship = :ship_id')->setParameter('ship_id', $ship->getId())
            ->leftJoin('p.parent', 'q')
            ->leftJoin('p.assignment', 'a', 'WITH', 'a.active = 1')
            ->leftJoin('a.persona', 'r')
            ->leftJoin('r.rank', 's')
            ->leftJoin('r.user', 'u')
            ->leftJoin('p.ship', 't')
            ->leftJoin('t.fleet', 'f')
            ->orderBy('s.order', 'asc')
            ->getQuery()
            ->setHint(Query::HINT_FORCE_PARTIAL_LOAD, true)
            ->execute();

        $positionCollection = new PositionCollection;

        foreach($positions as $position) {
            $positionCollection->getPositions()->add($position);
        }

        $form = $this->createForm(new PositionType(), $positionCollection);

        if ($request->getMethod() == 'POST') {

            $form->handleRequest($request);

            if ($form->isValid()) {
                foreach ($positionCollection->getPositions() as $position) {
                    $em = $this->getDoctrine()->getManager();
                    $em->persist($position);
                    $em->flush();
                }
                return $this->redirect($this->generateUrl('webb_ship_positions', array('fleet' => $ship->getFleet()->getShortname(), 'ship' => $ship->getShortname())));
            }

        }

        return array(
            'form' => $form->createView(),
        );
    }

    /**
     * @Route("{fleet}/{ship}/positions/{assignment}", name="webb_ship_assignment_end", requirements={"fleet" = "^stf\d+|^acad|^command"})
     */
    public function assignmentEndAction($ship, $fleet, $assignment, Request $request)
    {
        $this->loadShip($ship, $fleet);

        $assignment = $this->getDoctrine()->getRepository('WebbCharacterBundle:Assignment')->findOneBy(array('id' => $assignment));

        if(!$assignment || $assignment->getPosition()->getShip()->getId() != $ship->getId()) {
            throw $this->createNotFoundException(
                'Assignment not found'
            );
        }

        $date = new \DateTime();

        $assignment->setActive('false');
        $assignment->setEnddate($date);

        $em = $this->getDoctrine()->getManager();
        $em->persist($assignment);
        $em->flush();

        return $this->redirect($this->generateUrl('webb_ship_ship_view', array('fleet' => $ship->getFleet()->getShortname(), 'ship' => $ship->getShortname())));

    }

    function showRosterAction($ship) {

	    $positions = $this->getDoctrine()->getManager()->createQueryBuilder()
            ->select('p, a, r, q, s, u')
            ->from('WebbShipBundle:Position', 'p')
            ->where('p.ship = :ship_id')
            ->setParameter('ship_id', $ship->getId())
	        ->innerJoin('p.assignment', 'a')
            ->innerJoin('a.persona', 'r')
            ->innerJoin('p.parent', 'q')
            ->innerJoin('r.rank', 's')
            ->innerJoin('r.user', 'u')
            ->orderBy('s.order', 'ASC')
            ->getQuery()->execute();

        return $this->render('WebbShipBundle:Ship:roster.html.twig', array('positions' => $positions));

    }

    private function loadShip(&$ship, &$fleet) {

        $ship = $this->getDoctrine()->getRepository('WebbShipBundle:Ship')->findOneBy(array('shortname' => $ship));
        $fleet = $this->getDoctrine()->getRepository('WebbShipBundle:Fleet')->findOneBy(array('shortname' => $fleet));

        if (!$ship) {
            throw $this->createNotFoundException(
                'Ship not found'
            );
        }
        elseif ($ship->getFleet()->getId() != $fleet->getId()) {
            return $this->redirect($this->generateUrl('webb_ship_ship_edit', array('fleet' => $ship->getFleet()->getId(), 'ship' => $ship)));
        }

    }

    // Prepare the boxes for displaying
    private function prepareShowResult(Box &$box, $key) {
        if($box->getType() == "position") {
            // Ensure we're only getting one assignment in each box.
            $box->getPosition()->truncateAssignment();

            // Some logic to get the correct values to be used in templates
            if(count($box->getPosition()->getAssignment())){
                $assignment = $box->getPosition()->getAssignment();
                $box->temp->style = $assignment[0]->getPersona()->getRank()->getStyleName();
                $box->temp->name = $assignment[0]->getPersona()->getName();
                $box->temp->user = $assignment[0]->getPersona()->getUser();
            }
            else {
                $box->temp->style = "vacant";
                $box->temp->name = "Position Vacant";
                $box->temp->user = "Position Vacant";
            }
        }
    }
}


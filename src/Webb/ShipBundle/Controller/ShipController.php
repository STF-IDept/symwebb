<?php

namespace Webb\ShipBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Webb\ShipBundle\Form\Type\ShipType;
use Webb\ShipBundle\Entity\Ship;
use Symfony\Component\HttpFoundation\Request;
use Webb\MotdBundle\Entity\Box;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

// @todo: Add security entries for editing and creating a ship

/**
 * @Route("/")
 */
class ShipController extends Controller
{
    /**
     * @Route("fleet{fleet}/{shortname}", name="webb_ship_ship_view", requirements={"fleet" = "\d+"})
     * @Template("WebbShipBundle:Ship:show.html.twig")
     */
    public function showAction($fleet, $shortname)
    {

        $ship = $this->getDoctrine()->getRepository('WebbShipBundle:Ship')->findOneBy(array('shortname' => $shortname));

        if (!$ship) {
            throw $this->createNotFoundException(
                'Ship not found'
            );
        }
        elseif ($ship->getFleet()->getId() != $fleet) {
            return $this->redirect($this->generateUrl('webb_ship_ship_view', array('fleet' => $ship->getFleet()->getId(), 'shortname' => $shortname)));
        }

        /* Join the positions, assignments and personae */

        $query = $this->getDoctrine()->getManager()->createQueryBuilder()
            ->select('b, p, q, a, r, s, u')
            ->from('WebbMotdBundle:Box', 'b')
            ->where('b.ship = :ship_id')->setParameter('ship_id', $ship->getId())
            ->leftJoin('b.position', 'p')
            ->leftJoin('p.parent', 'q')
            ->leftJoin('p.assignment', 'a')
            ->andWhere('a.active = 1 OR b.type != \'position\'')
	    ->leftJoin('a.persona', 'r')
	    ->leftJoin('r.rank', 's')
	    ->leftJoin('r.user', 'u')
	    ->orderBy('b.boxorder', 'asc');

        $boxes = $query->getQuery()->execute();

        //array_walk($boxes, array($this, 'prepareBoxes'));

        return array(
            'ship' => $ship,
            'boxes' => $boxes,
        );
    }

    /**
     * @Route("ship/create", name="webb_ship_ship_create")
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

                return $this->redirect($this->generateUrl('webb_ship_ship_view', array('fleet' => $ship->getFleet()->getId(), 'shortname' => $ship->getShortname())));
            }
        }

        return array(
            'form' => $form->createView(),
        );

    }

    /**
     * @Route("fleet{fleet}/{shortname}/edit", name="webb_ship_ship_edit", requirements={"fleet" = "\d+"})
     * @Template("WebbShipBundle:Ship:edit.html.twig")
     */
    public function editAction($fleet, $shortname, Request $request)
    {
        $ship = $this->getDoctrine()->getRepository('WebbShipBundle:Ship')->findOneBy(array('shortname' => $shortname));
        $form = $this->createForm(new ShipType(), $ship);

        if (!$ship) {
            throw $this->createNotFoundException(
                'Ship not found'
            );
        }
        elseif ($ship->getFleet()->getId() != $fleet) {
            return $this->redirect($this->generateUrl('webb_ship_ship_edit', array('fleet' => $ship->getFleet()->getId(), 'shortname' => $shortname)));
        }

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
            'shortname' => $shortname,
        );
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

    // Prepare the boxes for displaying
    private function prepareBoxes(Box &$box, $key) {
        if($box->getType() == "position") {
            $box->getPosition()->truncateAssignment();
        }
    }
}

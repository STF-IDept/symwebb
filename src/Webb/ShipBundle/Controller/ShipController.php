<?php
/**
 * src/Webb/ShipBundle/Controller/ShipController.php
 */


namespace Webb\ShipBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Webb\ShipBundle\Form\Type\ShipType;
use Webb\ShipBundle\Entity\Ship;
use Symfony\Component\HttpFoundation\Request;

class ShipController extends Controller
{
    public function showAction($fleet, $shortname)
    {
        //$securityContext = new SecurityContext();
        //$ship = new Ship();

        $ship = $this->getDoctrine()->getRepository('WebbShipBundle:Ship')->findOneBy(array('shortname' => $shortname));

        if (!$ship) {
            throw $this->createNotFoundException(
                'Ship not found'
            );
        }
        elseif ($ship->getFleet()->getId() != $fleet) {
            return $this->redirect($this->generateUrl('webb_ship_ship_view', array('fleet' => $ship->getFleet()->getId(), 'shortname' => $shortname)));
        }

        $boxes = $this->getDoctrine()->getRepository('WebbMotdBundle:Box')->findBy(array('ship' => $ship->getId()), array('boxorder' => 'asc'));

        //$query = $this->getDoctrine()->getManager()->createQuery("SELECT b FROM (SELECT b FROM Webb\MotdBundle\Entity\Box b WHERE b.ship = {$ship->getId()} ORDER BY b.boxorder ASC) b");
        //$boxes = $query->getResult();
        /* Join the positions, assignments and personae */
        /*$roster = $this->getDoctrine()->getManager()->createQueryBuilder()
            ->select()
            ->from('WebbShipBundle:Position', 'p')
            ->addSelect('p')
            ->where('p.ship = :ship_id')->setParameter('ship_id', $ship->getId())
            ->innerJoin('p.assignment', 'a')
            ->innerJoin('a.persona', 'c')
            ->getQuery()
            ->getSQL();

        var_dump($roster);*/

        $test = $this->getDoctrine()->getManager()->createQueryBuilder()
            ->select('b, p, q, a')
            ->from('WebbMotdBundle:Box', 'b')
            ->where('b.ship = :ship_id')->setParameter('ship_id', $ship->getId())
            ->innerJoin('b.position', 'p')
            ->innerJoin('p.position', 'q')
            ->innerJoin('p.assignment', 'a')
            ->innerJoin('')

        $test->getQuery()->execute();

        //$roster = $this->getDoctrine()->getRepository('WebbShipBundle:Position')->findOneBy(array('ship' => $ship->getId()));

        //return $this->render('WebbCharacterBundle:Persona:index.html.twig', array('name' => $user));
        return $this->render('WebbShipBundle:Ship:show.html.twig', array('ship' => $ship, 'boxes' => $boxes));
    }

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

        return $this->render('WebbShipBundle:Ship:create.html.twig', array(
            'form' => $form->createView(),
        ));

    }

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

        return $this->render('WebbShipBundle:Ship:edit.html.twig', array(
            'form' => $form->createView(),
            'fleet' => $fleet,
            'shortname' => $shortname,
        ));
    }
}

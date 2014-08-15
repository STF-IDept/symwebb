<?php
/**
 * src/Webb/ShipBundle/Controller/FleetController.php
 */


namespace Webb\ShipBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Webb\ShipBundle\Form\Type\FleetType;
use Webb\ShipBundle\Entity\Fleet;
use Symfony\Component\HttpFoundation\Request;

class FleetController extends Controller
{
    public function showAction($shortname)
    {
        //$securityContext = new SecurityContext();
        //$fleet = new Fleet();

        $fleet = $this->getDoctrine()->getRepository('WebbShipBundle:Fleet')->findOneBy(array('shortname' => $shortname));

        if (!$fleet) {
            throw $this->createNotFoundException(
                'Fleet not found'
            );
        }
        //return $this->render('WebbCharacterBundle:Persona:show.html.twig', array('name' => $user));
        return $this->render('WebbShipBundle:Fleet:show.html.twig', array('fleet' => $fleet));
    }

    public function createAction(Request $request)
    {
        $fleet = new Fleet();
        $form = $this->createForm(new FleetType(), $fleet);

        if ($request->getMethod() == 'POST') {
            $form->bind($request);

            if ($form->isValid()) {
                // perform some action, such as saving the task to the database
                $em = $this->getDoctrine()->getManager();
                $em->persist($fleet);
                $em->flush();

                return $this->redirect($this->generateUrl('webb_ship_fleet_view', array('shortname' => $fleet->getShortname())));
            }
        }

        return $this->render('WebbShipBundle:Fleet:create.html.twig', array(
            'form' => $form->createView(),
        ));

    }

    public function editAction($shortname, Request $request)
    {
        $fleet = $this->getDoctrine()->getRepository('WebbShipBundle:Fleet')->findOneBy(array('shortname' => $shortname));
        $form = $this->createForm(new FleetType(), $fleet);

        if (!$fleet) {
            throw $this->createNotFoundException(
                'Fleet not found'
            );
        }

        if ($request->getMethod() == 'POST') {
            $form->bind($request);

            if ($form->isValid()) {
                // perform some action, such as saving the task to the database
                $em = $this->getDoctrine()->getManager();
                $em->persist($fleet);
                $em->flush();

                return $this->redirect($this->generateUrl('webb_ship_fleet_view', array('shortname' => $fleet->getShortname())));
            }
        }

        return $this->render('WebbShipBundle:Fleet:edit.html.twig', array(
            'form' => $form->createView(),
            'shortname' => $shortname,
        ));
    }
}

<?php
/**
 * src/Webb/ShipBundle/Controller/FleetController.php
 */


namespace Webb\ShipBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Webb\ShipBundle\Form\Type\FleetType;
use Webb\ShipBundle\Entity\Fleet;
use Symfony\Component\HttpFoundation\Request;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * @Route("/")
 */
class FleetController extends Controller
{
    /**
     * @Route("{fleet}", name="webb_ship_fleet_view", requirements={"fleet" = "^stf\d+|^acad|^command"})
     * @Template("WebbShipBundle:Fleet:show.html.twig")
     */
    public function showAction($fleet)
    {
        //$securityContext = new SecurityContext();
        //$fleet = new Fleet();

        $fleet = $this->getDoctrine()->getRepository('WebbShipBundle:Fleet')->findOneBy(array('shortname' => $fleet));

        if (!$fleet) {
            throw $this->createNotFoundException(
                'Fleet not found'
            );
        }
        //return $this->render('WebbCharacterBundle:Persona:show.html.twig', array('name' => $user));
        return $this->render('WebbShipBundle:Fleet:show.html.twig', array('fleet' => $fleet));
    }

    /**
     * @Route("fleet/create", name="webb_ship_fleet_create")
     * @Security("has_role('ROLE_FLEET_CREATE')")
     * @Template("WebbShipBundle:Fleet:create.html.twig")
     */
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

    /**
     * @Route("{fleet}/edit", name="webb_ship_fleet_edit", requirements={"fleet" = "^stf\d+|^acad|^command"})
     * @Security("has_role('ROLE_FLEET_EDIT')")
     * @Template("WebbShipBundle:Fleet:edit.html.twig")
     */
    public function editAction($fleet, Request $request)
    {
        $fleet = $this->getDoctrine()->getRepository('WebbShipBundle:Fleet')->findOneBy(array('shortname' => $fleet));
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
            'shortname' => $fleet->getShortname(),
        ));
    }
}

<?php

namespace Webb\CharacterBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Webb\CharacterBundle\Form\Type\PersonaType;
use Webb\CharacterBundle\Entity\Persona;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Doctrine\ORM\Query;
use Symfony\Component\Security\Core\Authorization\AuthorizationChecker;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;


/**
 * @Route("/character")
 */
class PersonaController extends Controller
{
    /**
     * @Route("/{id}", name="webb_character_view", requirements={"id" = "\d+"})
     * @Template("WebbCharacterBundle:Persona:show.html.twig")
     */
    public function showAction($id)
    {
        $persona = $this->getDoctrine()->getManager()->createQueryBuilder()
            ->select('c, i, r, s, pos, par, u, a')
            ->from('WebbCharacterBundle:Persona', 'c')
            ->where('c.id = :id')
            ->setParameter('id', $id)
            ->innerJoin('c.assignment', 'a')
            ->innerJoin('a.position', 'pos')
            ->innerJoin('pos.parent', 'par')
            ->innerJoin('pos.ship', 's')
            ->innerJoin('c.rank', 'r')
            ->innerJoin('c.image', 'i')
            ->innerJoin('c.user', 'u')
            ->orderBy('a.startdate', 'DESC')
            ->getQuery()
            ->setHint(Query::HINT_FORCE_PARTIAL_LOAD, true) //Believe it or not, but this saves us from having another SQL query run.
            ->getOneOrNullResult();

        if (!$persona) {
            throw $this->createNotFoundException(
                'No character found for id '.$id
            );
        }

        // Get the location of the image directories
        $location = $this->container->get('webb_file_location');
        $persona->getImage()->setWebRoot($location->getWebRoot());
        $persona->getImage()->setUploadDir($location->getDir('images'));

        return array('persona' => $persona);
    }


    /**
     * @Route("/create", name="webb_character_create")
     * @Security("has_role('ROLE_CHARACTER_CREATE')")
     * @Template("WebbCharacterBundle:Persona:create.html.twig")
     */
    public function createAction(Request $request)
    {
        $persona = new Persona();
        $form = $this->createForm(new PersonaType(), $persona);
        $persona->setUser($this->getUser());

        if ($request->getMethod() == 'POST') {
            $persona->getImage()->setName($persona->getName());
            $persona->getImage()->setFolder('character');

            $form->bind($request);

            if ($form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $em->persist($persona);
                $em->flush();

                return $this->redirect($this->generateUrl('webb_character_view', array('id' => 1)));
            }
        }

        return array('form' => $form->createView());

    }

    /**
     * @Route("/{id}/edit", name="webb_character_edit", requirements={"id" = "\d+"})
     * @Security("has_role('ROLE_CHARACTER_EDIT')")
     * @Template("WebbCharacterBundle:Persona:edit.html.twig")
     */
    public function editAction($id, Request $request)
    {
        if (!$persona = $this->getDoctrine()->getRepository('WebbCharacterBundle:Persona')->find($id)) {
            throw $this->createNotFoundException(
                'No character found for id '.$id
            );
        }

        $form = $this->createForm(new PersonaType(), $persona);

        $user = $this->container->get('security.context')->getToken()->getUser();

        // Check to see if the user owns the content, or if they have permissions to edit all characters
        if(($user->getId() != $persona->getUser()->getId()) && !$this->isGranted('ROLE_CHARACTER_EDIT_ALL')) {
            throw new AccessDeniedException("You are not authorised to edit this characters.");
        }

        if ($request->getMethod() == 'POST') {

            $form->bind($request);

            // get the location of the image directories and set some variables
            $location = $this->container->get('webb_file_location');
            $user->getImage()->setName($persona->getName());
            $user->getImage()->setFolder('character');
            $user->getImage()->setWebRoot($location->getWebRoot());
            $user->getImage()->setUploadDir($location->getDir('images'));

            if ($form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $em->persist($persona);
                $em->flush();

                return $this->redirect($this->generateUrl('webb_character_view', array('id' => $id)));
            }
        }

        return array('form' => $form->createView(), 'id' => $id, 'persona' => $persona);
    }

}

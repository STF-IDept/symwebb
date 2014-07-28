<?php
/**
 * src/Webb/CharacterBundle/Controller/PersonaController.php
 */


namespace Webb\CharacterBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Webb\CharacterBundle\Form\Type\PersonaType;
use Webb\CharacterBundle\Form\Type\AssignmentType;
use Webb\CharacterBundle\Entity\Persona;
use Symfony\Component\HttpFoundation\Request;
//use Symfony\Component\Security\Core\SecurityContext;
//use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class PersonaController extends Controller
{
    public function showAction($id)
    {
        //$securityContext = new SecurityContext();
        //$persona = new Persona($id);

        //$persona = $this->getDoctrine()->getRepository('WebbCharacterBundle:Persona')->find($id);

        $persona = $this->getDoctrine()->getManager()->createQueryBuilder()
            ->select('c, i')
            ->from('WebbCharacterBundle:Persona', 'c')
            ->where('c.id = :id')
            ->setParameter('id', $id)
            ->innerJoin('c.assignment', 'a')
            ->innerJoin('a.position', 'p')
            ->innerJoin('p.ship', 's')
            ->innerJoin('c.rank', 'r')
            ->innerJoin('c.image', 'i')
            ->innerJoin('c.user', 'u')
            ->orderBy('a.startdate', 'DESC')
            ->getQuery()->getOneOrNullResult();

        if (!$persona) {
            throw $this->createNotFoundException(
                'No character found for id '.$id
            );
        }
        //return $this->render('WebbCharacterBundle:Persona:show.html.twig', array('name' => $user));
        return $this->render('WebbCharacterBundle:Persona:show.html.twig', array('persona' => $persona));
    }

    public function createAction(Request $request)
    {
        $persona = new Persona();
        $form = $this->createForm(new PersonaType(), $persona);
        $persona->setUser($this->getUser());

        if ($request->getMethod() == 'POST') {
            $form->bind($request);

            if ($form->isValid()) {
                // perform some action, such as saving the task to the database
                //$persona->getImage()->upload();
                $em = $this->getDoctrine()->getManager();
                $em->persist($persona);
                $em->flush();

                return $this->redirect($this->generateUrl('webb_character_view', array('id' => 1)));
            }
        }

        return $this->render('WebbCharacterBundle:Persona:create.html.twig', array(
            'form' => $form->createView(),
        ));

    }

    public function editAction($id, Request $request)
    {
        $persona = $this->getDoctrine()->getRepository('WebbCharacterBundle:Persona')->find($id);
        $form = $this->createForm(new PersonaType(), $persona);

        if (!$persona) {
            throw $this->createNotFoundException(
                'No character found for id '.$id
            );
        }

        if ($request->getMethod() == 'POST') {
            $form->bind($request);

            if ($form->isValid()) {
                // perform some action, such as saving the task to the database
                //$persona->getImage()->upload();
                $em = $this->getDoctrine()->getManager();
                $em->persist($persona);
                $em->flush();

                return $this->redirect($this->generateUrl('webb_character_view', array('id' => $id)));
            }
        }

        return $this->render('WebbCharacterBundle:Persona:edit.html.twig', array(
            'form' => $form->createView(),
            'id' => $id,
            'persona' => $persona,
        ));
    }
}

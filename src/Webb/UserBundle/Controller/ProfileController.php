<?php

namespace Webb\UserBundle\Controller;

use FOS\UserBundle\Controller\ProfileController as BaseController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use FOS\UserBundle\Model\UserInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\Request;
use FOS\UserBundle\Event\GetResponseUserEvent;
use FOS\UserBundle\FOSUserEvents;
use FOS\UserBundle\Event\FormEvent;
use Symfony\Component\HttpFoundation\RedirectResponse;
use FOS\UserBundle\Event\FilterUserResponseEvent;

/**
 * @Security("has_role('ROLE_USER')")
 * @Route("/user")
 */
class ProfileController extends BaseController
{
    /**
     * @Route("/", name="fos_user_profile_show", defaults={"id" = false})
     * @Route("/{id}", name="webb_user_profile_show_byid", requirements={"id" = "\d+"})
     * @Template("FOSUserBundle:Profile:show.html.twig")
     */
    public function showAction($id)
    {
        if(!$id) {
            $id = $this->container->get('security.context')->getToken()->getUser()->getId();
        }

        $profile = $this->container->get('doctrine')->getManager()->createQueryBuilder()
            ->select('u, r1, pe, r2, a, po, pa, s, i')
            ->from('WebbUserBundle:User', 'u')
            ->where('u.id = :id')
            ->andWhere('a.active = true')
            ->setParameter('id', $id)
            ->innerJoin('u.rank', 'r1')
            ->innerJoin('u.persona', 'pe')
            ->innerJoin('pe.rank', 'r2')
            ->innerJoin('pe.assignment', 'a')
            ->innerJoin('a.position', 'po')
            ->innerJoin('po.parent', 'pa')
            ->innerJoin('po.ship', 's')
            ->innerJoin('u.image', 'i')
            ->getQuery()->getOneOrNullResult();

        if (!$profile) {
            throw new NotFoundHttpException(
                'No profile found for id '.$id
            );
        }

        return array('user' => $profile);
    }

    /**
     * @Route("/edit/", name="fos_user_profile_edit", defaults={"id" = false})
     * @Route("/{id}/edit/", name="webb_user_profile_edit_byid", requirements={"id" = "\d+"})
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     * @Template("FOSUserBundle:Profile:edit.html.twig")
     */
    public function editAction($id, Request $request)
    {
        if (!$id) {
            $user = $this->container->get('security.context')->getToken()->getUser();
        }
        else {
            if (false === $this->container->get('security.authorization_checker')->isGranted('ROLE_PROFILE_EDIT')) {
                throw new AccessDeniedException("You are not authorised to edit other users' profiles.");
            }
            $user = $this->container->get('doctrine')->getRepository('WebbUserBundle:User')->find($id);
        }

        if (!$user) {
            throw new NotFoundHttpException(
                'No profile found for id '.$id
            );
        }

        /** @var $dispatcher \Symfony\Component\EventDispatcher\EventDispatcherInterface */
        $dispatcher = $this->container->get('event_dispatcher');

        $event = new GetResponseUserEvent($user, $request);
        $dispatcher->dispatch(FOSUserEvents::PROFILE_EDIT_INITIALIZE, $event);

        if (null !== $event->getResponse()) {
            return $event->getResponse();
        }

        /** @var $formFactory \FOS\UserBundle\Form\Factory\FactoryInterface */
        $formFactory = $this->container->get('fos_user.profile.form.factory');

        $form = $formFactory->createForm();
        $form->setData($user);

        if ('POST' === $request->getMethod()) {
            $form->bind($request);

            if ($form->isValid()) {
                /** @var $userManager \FOS\UserBundle\Model\UserManagerInterface */
                $userManager = $this->container->get('fos_user.user_manager');

                $event = new FormEvent($form, $request);
                $dispatcher->dispatch(FOSUserEvents::PROFILE_EDIT_SUCCESS, $event);

                $userManager->updateUser($user);

                if (null === $response = $event->getResponse()) {
                    $url = $this->container->get('router')->generate('fos_user_profile_show');
                    $response = new RedirectResponse($url);
                }

                $dispatcher->dispatch(FOSUserEvents::PROFILE_EDIT_COMPLETED, new FilterUserResponseEvent($user, $request, $response));

                return $response;
            }
        }

        return array('form' => $form->createView());
    }
}

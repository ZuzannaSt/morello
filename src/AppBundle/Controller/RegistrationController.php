<?php

/**
 * RegistrationController
 *
 * PHP version 5
 *
 * @author Zuzanna Stolińska <zuzanna.st@gmail.com>
 * @link wierzba.wzks.uj.edu.pl/~11_stolinska/symfony_projekt
 */

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use AppBundle\Form\UserType;
use AppBundle\Entity\User;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

/**
 * Class RegistrationController
 * @package AppBundle\Controller
 */
class RegistrationController extends Controller
{
    /**
     * Class RegistrationsController
     * @package AppBundle\Controller
     * @param Request request
     * @Route("/register", name="user_registration")
     */
    public function registerAction(Request $request)
    {
        $user = new User();
        $form = $this->createForm(new UserType(), $user);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $encoder = $this->get('security.encoder_factory')
                ->getEncoder($user);
            $password = $encoder->encodePassword($user->getPlainPassword(), $user->getSalt());
            $user->setPassword($password);

            $role = $this->getDoctrine()
                ->getRepository('AppBundle:Role')
                ->findOneBy(array('role' => 'ROLE_USER'));

            $user->addRole($role);

            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            $redirectUrl = $this->generateUrl('registration_confirmed');

            return $this->redirect($redirectUrl);
        }

        return $this->render(
            'AppBundle:Registration:register.html.twig',
            array('form' => $form->createView())
        );
    }

    /**
     * Confirmed Action
     *
     * @Route("/confirmed", name="registration_confirmed")
     *
     */
    public function confirmedAction()
    {
        return $this->render('AppBundle:Registration:confirmed.html.twig');
    }
}

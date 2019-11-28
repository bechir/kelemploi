<?php

/*
 * This file is part of the Kelemploi application.
 *
 * (c) Bechir Ba <bechiirr71@gmail.com>
 */

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController as Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;

class SecurityController extends Controller
{
    public function login(Request $request, AuthenticationUtils $helper): Response
    {
        $this->checkAuth();

        return $this->render('security/login.html.twig', [
            'last_username' => $helper->getLastUsername(),
            'error' => $helper->getLastAuthenticationError(),
            'message' => '',
        ]);
    }

    public function register(Request $request, UserPasswordEncoderInterface $passwordEncoder, AuthenticationUtils $helper): Response
    {
        $this->checkAuth($request);

        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $password = $passwordEncoder->encodePassword($user, $user->getPlainPassword());
            $user->setPassword($password);
            $user->setRole(['ROLE_USER']);

            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            $this->addFlash('success', 'user.registered_successfully');

            $token = new UsernamePasswordToken($user, null, 'main', $user->getRoles());
            $this->get('security.token_storage')->setToken($token);

            $this->get('session')->set('_security_main', serialize($token));

            $event = new InteractiveLoginEvent($request, $token);
            $this->get('event_dispatcher')->dispatch('security.interactive_login', $event);

            if ($request->getSession()->has('redirect_to')) {
                return new RedirectResponse($request->getSession()->get('redirect_to'));
            }

            return $this->redirectToRoute('index');
        }

        return $this->render('security/register.html.twig', [
            'form' => $form->createView(),
            'error' => $helper->getLastAuthenticationError(),
        ]);
    }

    public function reset(Request $request)
    {
        return $this->render('security/reset.html.twig');
    }

    public function logout(Request $request)
    {
        throw new \Exception('This should never be reached!');
    }

    public function checkAuth()
    {
        if ($this->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            return $this->redirectToRoute('index');
        }
    }
}

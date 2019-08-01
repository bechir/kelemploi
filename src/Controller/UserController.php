<?php

/*
 * This file is part of the Kelemploi application.
 *
 * (C) Bechir Ba <bechiirr71@gmail.com>
 */

namespace App\Controller;

use App\Form\EditProfileType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController as Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Event\CandidateEvent;
use App\Entity\User;
use App\Event\CandidateEvents;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class UserController extends Controller
{
    public function list(): Response
    {
        return $this->render('candidate/listing.html.twig', [
            'list' => $this->getDoctrine()->getRepository(User::class)->findCandidates(),
        ]);
    }

    public function show(User $user, EventDispatcherInterface $dispatcher): Response
    {
        if (!$user) {
            $this->addFlash('danger', "L'utilisateur' n'existe pas.");

            return $this->redirectToRoute('index');
        }

        $event = new CandidateEvent($user);
        $dispatcher->dispatch(CandidateEvents::CANDIDATE_VIEWED, $event);

        return $this->render('candidate/show.html.twig', [
            'user' => $user,
        ]);
    }

    /**
     * @IsGranted("ROLE_USER")
     */
    public function resume(): Response
    {
        $user = $this->getUser();

        return $this->render('user/resume.html.twig', [
            'user' => $user,
            'active' => 'resume',
        ]);
    }

    /**
     * @IsGranted("ROLE_USER")
     */
    public function editResume(): Response
    {
        $user = $this->getUser();

        return $this->render('user/edit-resume.html.twig', [
            'user' => $user,
            'active' => 'edit-resume',
        ]);
    }

    /**
     * @IsGranted("ROLE_USER")
     */
    public function dashboard(): Response
    {
        $user = $this->getUser();

        return $this->render('user/dashboard.html.twig', [
            'user' => $user,
            'active' => 'dashboard',
        ]);
    }

    /**
     * @IsGranted("ROLE_USER")
     */
    public function editProfile(Request $request): Response
    {
        $user = $this->getUser();

        $form = $this->createForm(EditProfileType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            $this->addFlash('success', 'user.edit_profile.success');
        }

        return $this->render('user/edit-profile.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
            'active' => 'edit-profile',
        ]);
    }

    /**
     * @IsGranted("ROLE_USER")
     */
    public function bookmarked(): Response
    {
        $user = $this->getUser();

        return $this->render('user/bookmarked.html.twig', [
            'user' => $user,
            'active' => 'bookmarked',
        ]);
    }

    /**
     * @IsGranted("ROLE_USER")
     */
    public function applied(): Response
    {
        $user = $this->getUser();

        return $this->render('user/applied.html.twig', [
            'user' => $user,
            'active' => 'applied-jobs',
        ]);
    }

    /**
     * @IsGranted("ROLE_USER")
     */
    public function messages(): Response
    {
        $user = $this->getUser();

        return $this->render('user/messages.html.twig', [
            'user' => $user,
            'active' => 'messages',
        ]);
    }

    /**
     * @IsGranted("ROLE_USER")
     */
    public function settings(): Response
    {
        $user = $this->getUser();

        return $this->render('user/settings.html.twig', [
            'user' => $user,
            'active' => 'settings',
        ]);
    }
}

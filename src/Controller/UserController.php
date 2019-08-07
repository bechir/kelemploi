<?php

/*
 * This file is part of the Kelemploi application.
 *
 * (C) Bechir Ba <bechiirr71@gmail.com>
 */

namespace App\Controller;

use App\Form\ResumeType;
use App\Form\EditProfileType;
use App\Event\CandidateEvent;
use App\Event\CandidateEvents;
use App\Entity\User;
use App\Entity\Resume;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class UserController extends AbstractController
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
    public function resume(UserInterface $user = null): Response
    {
        return $this->render('candidate/resume.html.twig', [
            'user' => $user,
            'resume' => $user->getResume(),
            'active' => 'resume',
        ]);
    }

    /**
     * @IsGranted("ROLE_USER")
     */
    public function addResume(Request $request, UserInterface $user = null): Response
    {
        if($user->haveResume())
            return $this->redirectToRoute('edit_resume');

        $resume = new Resume();
        $form = $this->createForm(ResumeType::class, $resume);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $user->setResume($resume);

            foreach ($resume->getEducations() as $educ) {
                $educ->setResume($resume);
            }

            foreach ($resume->getWorkExperiences() as $xp) {
                $xp->setResume($resume);
            }

            foreach ($resume->getProSkills() as $skills) {
                $skills->setResume($resume);
            }
            
            $em = $this->getDoctrine()->getManager();

            $em->persist($resume);
            $em->persist($user);

            $em->flush();

            $this->addFlash('success', 'resume.create_success');

            return $this->redirectToRoute('resume');
        }

        return $this->render('candidate/add-resume.html.twig', [
            'user' => $user,
            'form' => $form->createView()
        ]);
    }

    /**
     * @IsGranted("ROLE_USER")
     */
    public function editResume(UserInterface $user = null): Response
    {
        if(!$user->haveResume())
            return $this->redirectToRoute('add_resume');

        return $this->render('candidate/edit-resume.html.twig', [
            'user' => $user,
            'resume' => $user->getResume(),
            'active' => 'edit-resume',
        ]);
    }

    /**
     * @IsGranted("ROLE_USER")
     */
    public function dashboard(): Response
    {
        $user = $this->getUser();

        return $this->render('candidate/dashboard.html.twig', [
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

        return $this->render('candidate/edit-profile.html.twig', [
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

        return $this->render('candidate/bookmarked.html.twig', [
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

        return $this->render('candidate/applied.html.twig', [
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

        return $this->render('candidate/messages.html.twig', [
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

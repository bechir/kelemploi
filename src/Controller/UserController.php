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
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Apply;
use App\Form\Resume\ResumeEditAboutType;
use App\Form\Resume\ResumeEditEducationsType;
use App\Form\Resume\ResumeEditProfessionalSkillsType;
use App\Form\Resume\ResumeEditSkillsType;
use App\Form\Resume\ResumeEditWorkExperiencesType;
use Symfony\Component\Form\Exception\LogicException as SymfonyFormLogicException;

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

        if($this->getUser() && $this->getUser()->isEmployer()) {
            $event = new CandidateEvent($user);
            $dispatcher->dispatch(CandidateEvents::CANDIDATE_VIEWED, $event);
        }

        return $this->render('candidate/show.html.twig', [
            'user' => $user,
            'resume' => $user->getResume()
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

            foreach ($resume->getProSkills() as $skill) {
                $skill->setResume($resume);
            }

            foreach ($resume->getPortfolios() as $p) {
                $p->setResume($resume);
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
    public function editResume(Request $request, EntityManagerInterface $em, UserInterface $user = null): Response
    {
        $resume = $user->getResume();

        if(!$resume) {
            return $this->redirectToRoute('add_resume');
        }

        // Perform each form field individually
        $aboutForm = $this->createForm(ResumeEditAboutType::class, $resume);
        $skillsForm = $this->createForm(ResumeEditSkillsType::class, $resume);
        $workExpForm = $this->createForm(ResumeEditWorkExperiencesType::class, $resume);
        $educationForm = $this->createForm(ResumeEditEducationsType::class, $resume);
        $proSkillsForm = $this->createForm(ResumeEditProfessionalSkillsType::class, $resume);

        $aboutForm->handleRequest($request);
        $skillsForm->handleRequest($request);
        $workExpForm->handleRequest($request);
        $educationForm->handleRequest($request);
        $proSkillsForm->handleRequest($request);

        $forms = [$aboutForm, $skillsForm, $workExpForm, $educationForm, $proSkillsForm];

        if($request->isMethod('POST')) {
            foreach ($forms as $form) {
                try {
                    if(!$form->isValid()) {
                        $this->addFlash('danger', 'text.edit_error');
                    }

                    if($form->isSubmitted() && $form->isValid()) {
                        $em->flush();
                        
                        $this->addFlash('success', 'text.edit_success');
                        return $this->redirectToRoute('edit_resume');
                    }
                } catch (SymfonyFormLogicException $e) {
                    // the form is not submitted, nothing to do.
                }
            }

            // Handle submission of resume title field
            $resumeTitleToken = $request->request->get('resume_edit_title__token');
            if($this->isCsrfTokenValid('resume.edit.title.token', $resumeTitleToken)) {
                $resumeTitle = $request->request->get('resume_edit_title_value');
                $user->getResume()->setTitle($resumeTitle);

                $em->flush();

                $this->addFlash('success', 'text.edit_success');
                return $this->redirectToRoute('edit_resume');
            } else {
                $this->addFlash('danger', 'app.invalid_csrf_token');
            }
        }

        return $this->render('candidate/edit-resume.html.twig', [
            'user' => $user,
            'resume' => $user->getResume(),
            'active' => 'edit-resume',
            'aboutForm' => $aboutForm->createView(),
            'skillsForm' => $skillsForm->createView(),
            'workExpForm' => $workExpForm->createView(),
            'educationForm' => $educationForm->createView(),
            'proSkillsForm' => $proSkillsForm->createView()
        ]);
    }

    /**
     * @IsGranted("ROLE_USER")
     */
    public function deleteResume(UserInterface $user = null): Response
    {
        if($user->haveResume()) {
            $em = $this->getDoctrine()->getManager();

            $em->remove($user->getResume());
            $user->setResume(null);
            $em->flush();
            
            $this->addFlash('success', 'resume.delete_success');
        }

        return $this->redirectToRoute('user_dashboard');
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
    public function applied(UserInterface $user = null, EntityManagerInterface $em): Response
    {
        $applies = $em->getRepository(Apply::class)->findBy(['candidate' => $user]);

        return $this->render('candidate/applied.html.twig', [
            'user' => $user,
            'applies' => $applies,
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

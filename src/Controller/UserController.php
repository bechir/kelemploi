<?php

/*
 * This file is part of the Kelemploi application.
 *
 * (C) Bechir Ba <bechiirr71@gmail.com>
 */

namespace App\Controller;

use App\Entity\User;
use App\Entity\Apply;
use App\Entity\Application as Job;
use App\Entity\Resume;
use App\Form\Resume\ResumeType;
use App\Form\EditProfileType;
use App\Form\Resume\ResumeEditAboutType;
use App\Form\Resume\ResumeEditEducationsType;
use App\Form\Resume\ResumeEditProfessionalSkillsType;
use App\Form\Resume\ResumeEditSkillsType;
use App\Form\Resume\ResumeEditWorkExperiencesType;
use App\Event\CandidateEvent;
use App\Event\CandidateEvents;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\Exception\LogicException as SymfonyFormLogicException;
use Symfony\Component\HttpFoundation\JsonResponse;

class UserController extends AbstractController
{
    public function list(): Response
    {
        if(!$this->isGranted('ROLE_EMPLOYER')) {
            return $this->render('candidate/access-limited.html.twig');
        }

        return $this->render('candidate/listing.html.twig', [
            'list' => $this->getDoctrine()->getRepository(User::class)->findCandidates(),
        ]);
    }

    public function show(Resume $resume, EventDispatcherInterface $dispatcher, UserInterface $user = null): Response
    {
        $candidate = $resume->getUser();

        if($user && $user->isEmployer()) {
            $event = new CandidateEvent($candidate);
            $dispatcher->dispatch(CandidateEvents::CANDIDATE_VIEWED, $event);
        }

        return $this->render('candidate/show.html.twig', [
            'user' => $candidate,
            'resume' => $resume
        ]);
    }

    /**
     * @IsGranted("ROLE_CANDIDATE")
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
     * @IsGranted("ROLE_CANDIDATE")
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
     * @IsGranted("ROLE_CANDIDATE")
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
        $workExperiencesForm = $this->createForm(ResumeEditWorkExperiencesType::class, $resume);
        $educationsForm = $this->createForm(ResumeEditEducationsType::class, $resume);
        $proSkillsForm = $this->createForm(ResumeEditProfessionalSkillsType::class, $resume);

        $aboutForm->handleRequest($request);
        $skillsForm->handleRequest($request);
        $workExperiencesForm->handleRequest($request);
        $educationsForm->handleRequest($request);
        $proSkillsForm->handleRequest($request);

        $forms = [$aboutForm, $skillsForm, $workExperiencesForm, $educationsForm, $proSkillsForm];

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
            'workExperiencesForm' => $workExperiencesForm->createView(),
            'educationsForm' => $educationsForm->createView(),
            'proSkillsForm' => $proSkillsForm->createView()
        ]);
    }

    /**
     * @IsGranted("ROLE_CANDIDATE")
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
    public function dashboard(UserInterface $user = null): Response
    {
        if($this->isGranted('ROLE_EMPLOYER')) {
            return $this->redirectToRoute('company_dashboard');
        }
        elseif($this->isGranted('ROLE_ADMIN')) {
            return $this->redirectToRoute('admin_index');
        }
        else {
            return $this->render('candidate/dashboard.html.twig', [
                'user' => $user,
                'active' => 'dashboard',
            ]);
        }
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
     * @IsGranted("ROLE_CANDIDATE")
     */
    public function bookmarked(UserInterface $user): Response
    {
        return $this->render('candidate/bookmarked.html.twig', [
            'user' => $user,
            'jobs' => $user->getBookmarkedJobs(),
            'active' => 'bookmarked',
        ]);
    }

    /**
     * @IsGranted("ROLE_USER")
     */
    public function toogleJobBookmark(Request $request, EntityManagerInterface $em, UserInterface $user = null): JsonResponse
    {
        $job = $em->getRepository(Job::class)->findOneBySlug($request->query->get('slug'));
        $action = $request->query->get('action');
    
        if($job && $user && in_array($action, ['add', 'remove'])) {
            if($action == 'add') {
                $user->addBookmarkedJob($job);
            } else {
                $user->removeBookmarkedJob($job);
            }

            $em->flush();
            return new JsonResponse('success');
        }

        return new JsonResponse('erorr');
    }

    /**
     * @IsGranted("ROLE_USER")
     */
    public function jobRemoveFromFavorites(Job $job, EntityManagerInterface $em, UserInterface $user = null): Response
    {
        if($job && $user) {
            $user->removeBookmarkedJob($job);
            $em->flush();

            $this->addFlash('success', 'Offre supprimée des favoris.');
            return $this->redirectToRoute('user_bookmarked');
        }
    }

    /**
     * @IsGranted("ROLE_CANDIDATE")
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
     * @IsGranted("ROLE_CANDIDATE")
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

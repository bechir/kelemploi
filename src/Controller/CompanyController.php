<?php

/*
 * This file is part of the Kelemploi application.
 *
 * (C) Bechir Ba <bechiirr71@gmail.com>
 */

namespace App\Controller;

use App\Entity\Application;
use App\Entity\Company;
use App\Entity\ResumeBookmark;
use App\Form\CompanyType;
use Doctrine\ORM\EntityManagerInterface;
use Proxies\__CG__\App\Entity\Resume;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\User\UserInterface;

class CompanyController extends AbstractController
{
    public function list(Request $request): Response
    {
        $companies = $this->getDoctrine()->getRepository(Company::class)->findAll();

        return $this->render('company/listing.html.twig', [
            'companies' => $companies,
        ]);
    }

    public function show(Company $company): Response
    {
        return $this->render('company/show.html.twig', [
            'company' => $company,
        ]);
    }

    public function openedJobs(Company $company): Response
    {
        $list = $this->getDoctrine()
            ->getRepository(Application::class)
                ->findBy(['company' => $company]);

        return $this->render('company/open-job.html.twig', [
            'jobs' => $list,
        ]);
    }

    /**
     * IsGranted("ROLE_EMPLOYER")
     */
    public function dashboard(UserInterface $user = null): Response
    {
        return $this->render('company/dashboard.html.twig', [
            'user' => $user,
            'active' => 'dashboard',
            'company' => $user->getCompany(),
        ]);
    }

    /**
     * IsGranted("ROLE_USER").
     */
    public function editProfile(Request $request, UserInterface $user = null): Response
    {
        // Acces limité aux employeurs
        if(!$this->isGranted('ROLE_EMPLOYER'))  {
            return $this->render('candidate/access-limited.html.twig');
        }

        $em = $this->getDoctrine()->getManager();

        $company = $user->getCompany();

        if (!$company) {
            $company = new Company();
        }

        $form = $this->createForm(CompanyType::class, $company);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $company->addOwner($user);
            $em->persist($company);
            $em->flush();

            $this->addFlash('success', 'company.edit_profile.success');
        }

        return $this->render('company/edit-profile.html.twig', [
            'company' => $company,
            'form' => $form->createView(),
            'active' => 'edit-profile',
        ]);
    }

    /**
     * IsGranted("ROLE_EMPLOYER") 
     */
    public function manageJobs(UserInterface $user = null): Response
    {
        $jobs = [];
        $em = $this->getDoctrine()->getManager();
        $company = $user->getCompany();
        if($company) {
            $jobs = $this->getDoctrine()
                ->getRepository(Application::class)
                    ->findBy(['company' => $company]);
        }

        return $this->render('company/manage-jobs.html.twig', [
            'user' => $user,
            'jobs' => $jobs,
            'company' => $company,
            'active' => 'manage-jobs'
        ]);
    }

    /**
     * IsGranted("ROLE_EMPLOYER") 
     */
    public function manageCandidates(UserInterface $user = null): Response
    {
        $company = $user->getCompany();

        return $this->render('company/manage-candidates.html.twig', [
            'user' => $user,
            'company' => $company,
            'active' => 'manage-candidates'
        ]);
    }

    /**
     * IsGranted("ROLE_EMPLOYER")
     */
    public function shortlistedResumes(Request $request, UserInterface $user = null, EntityManagerInterface $em): Response
    {
        $company = $user->getCompany();
        $bookmarkedResumes = $em->getRepository(ResumeBookmark::class)->findByCompany($company);

        return $this->render('company/shortlisted-resumes.html.twig', [
            'bookmarkedResumes' => $bookmarkedResumes,
            'active' => 'shortlisted',
            'company' => $company
        ]);
    }

    /**
     * IsGranted("ROLE_EMPLOYER") 
     */
    public function messages(UserInterface $user = null): Response
    {
        $em = $this->getDoctrine()->getManager();
        $company = $user->getCompany();

        return $this->render('company/messages.html.twig', [
            'user' => $user,
            'company' => $company,
            'active' => 'messages'
        ]);
    }

    /**
     * IsGranted("ROLE_EMPLOYER") 
     */
    public function pricing(UserInterface $user = null): Response
    {
        $em = $this->getDoctrine()->getManager();
        $company = $user->getCompany();

        return $this->render('company/pricing.html.twig', [
            'user' => $user,
            'company' => $company,
            'active' => 'pricing'
        ]);
    }

}

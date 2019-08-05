<?php

/*
 * This file is part of the Kelemploi application.
 *
 * (C) Bechir Ba <bechiirr71@gmail.com>
 */

namespace App\Controller;

use App\Entity\Application;
use App\Entity\Company;
use App\Form\CompanyType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

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
     * IsGranted("ROLE_USER").
     */
    public function dashboard(): Response
    {
        // Acces limité aux employeurs
        if(!$this->isGranted('ROLE_EMPLOYER'))  {
            return $this->render('candidate/access-limited.html.twig');
        }

        $user = $this->getUser();

        return $this->render('company/dashboard.html.twig', [
            'user' => $user,
            'active' => 'dashboard',
            'company' => $user->getCompany(),
        ]);
    }

    /**
     * IsGranted("ROLE_USER").
     */
    public function editProfile(Request $request): Response
    {
        // Acces limité aux employeurs
        if(!$this->isGranted('ROLE_EMPLOYER'))  {
            return $this->render('candidate/access-limited.html.twig');
        }

        $em = $this->getDoctrine()->getManager();

        $company = $this->getUser()->getCompany();

        if (!$company) {
            $company = new Company();
        }

        $form = $this->createForm(CompanyType::class, $company);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $company->addOwner($this->getUser());
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
}

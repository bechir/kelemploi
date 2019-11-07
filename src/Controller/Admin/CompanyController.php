<?php

/*
 * This file is part of the Beta application.
 *
 * (c) Bechir Ba <bechiirr71@gmail.com>
 */

namespace App\Controller\Admin;

use App\Entity\Company;
use App\Entity\Application;
use App\Event\ItemsEvent;
use App\Event\ItemsEvents;
use App\Form\CompanyType;
use App\Repository\CompanyRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Controller to manage companies admin pages.
 *
 * @Route("/admin/companies")
 * @IsGranted("ROLE_ADMIN")
 *
 * @author Bechir Ba <bechiirr71@gmail.com>
 */
class CompanyController extends AbstractController
{
    /**
     * @Route("/{page}", name="admin_companies_list", requirements={"page"="\d+"}, defaults={"page"=1})
     */
    public function index(Request $request, int $page = 1, CompanyRepository $companyRepository): Response
    {
        $companies = $companyRepository->adminGetCompanies($page);

        return $this->render('admin/company/list.html.twig', [
            'companies' => $companies,
        ]);
    }

    /**
     * @Route("/details/{company}", name="admin_company_show")
     */
    public function show(Request $request, Company $company, EntityManagerInterface $em): Response
    {
        if (!$company) {
            return $this->render('admin/pages/404.html.twig');
        }

        return $this->render('admin/company/show.html.twig', [
            'company' => $company,
            'jobs' => $em->getRepository(Application::class)->findByCompany($company),
        ]);
    }

    /**
     * @Route("/create", name="admin_company_create")
     */
    public function create(Request $request, EntityManagerInterface $em, UserInterface $user = null): Response
    {
        $company = new Company();
        $form = $this->createForm(CompanyType::class, $company);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $company->setAdminOwner($user);
            $em->persist($company);

            $em->flush();

            $this->addFlash('success', 'company.created');

            return $this->redirectToRoute('admin_company_show', [
                'company' => $company->getId(),
            ]);
        }

        return $this->render('admin/company/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/edit/{slug}", name="admin_company_edit")
     */
    public function edit(Company $company, Request $request, EntityManagerInterface $em, UserInterface $user = null): Response
    {
        $form = $this->createForm(CompanyType::class, $company);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($company);

            $em->flush();

            $this->addFlash('success', 'edit_success');

            return $this->redirectToRoute('admin_company_show', [
                'company' => $company->getId(),
            ]);
        }

        return $this->render('admin/company/edit.html.twig', [
            'form' => $form->createView(),
            'company' => $company,
        ]);
    }

    /**
     * @Route("/disable", name="admin_company_disable", methods={"POST"})
     */
    public function disableCompany(Request $request): JsonResponse
    {
        return $this->setCompanyEnabled($request, false);
    }

    /**
     * @Route("/enable", name="admin_company_enable", methods={"POST"})
     */
    public function enableCompany(Request $request): JsonResponse
    {
        return $this->setCompanyEnabled($request, true);
    }

    /**
     * @Route("/delete/{id}", name="admin_company_delete", methods={"POST"})
     */
    public function deleteCompany(Company $company, EntityManagerInterface $em, EventDispatcherInterface $dispatcher)
    {
        if (!$company) {
            $this->addFlash('success', "L'organisation est introuvable.");
        } else {
            $jobs = $em->getRepository(Application::class)->findByCompany($company);

            if ($jobs) {
                foreach ($jobs as $job) {
                    $em->remove($job);

                    $event = new ItemsEvent($job);
                    $dispatcher->dispatch(ItemsEvents::JOB_DELETED, $event);
                }
            }

            $user = $company->getAdminOwner();
            if ($user) {
                $user->removeAdminCompany($company);
                $em->persist($user);
            }

            $owners = $company->getOwners();

            if ($owners) {
                foreach ($owners as $owner) {
                    $company->removeOwner($owner);
                }
            }

            $em->remove($company);
            $em->flush();
            $this->addFlash('success', "L'organisation a été supprimée avec toutes les offres liées.");
        }

        return $this->redirectToRoute('admin_companies_list');
    }

    /**
     * @Route("/confirm", name="admin_company_confirm", methods={"POST"})
     */
    public function confirmCompany(Request $request, \Swift_Mailer $mailer, ContainerInterface $container): JsonResponse
    {
        $em = $this->getDoctrine()->getManager();
        $company = $em->find(Company::class, $request->request->get('id'));

        if ($company) {
            $company->setConfirmed(true);
            $company->setIsActivated(true);
            $em->flush();

            $fromEmail = $container->getParameter('website.email');
            $fromName = $container->getParameter('website.name');
            $user = $company->getUser();
            $toEmail = $user->getEmail();
            $toName = $user->getFullname();

            // If the owner is an admin, it is not necessary to send an email
            if ($user != $company->getAdminOwner()) {
                $message = (new \Swift_Message())
                    ->setFrom([$fromEmail => $fromName])
                    ->setTo([$toEmail => $toName])
                    ->setSubject('Votre organisation a été confirmée')
                    ->setContentType('text/html')
                    ->setBody(
                        $this->renderView(
                            'emails/company/confirmed.html.twig',
                            [
                                'username' => $toName,
                                'company_name' => $company->getName(),
                            ]
                        ),
                        'text/html'
                    )
                    ->addPart(
                        $this->renderView(
                            'emails/company/confirmed.txt.twig',
                            [
                                'username' => $toName,
                                'company_name' => $company->getName(),
                            ]
                        ),
                        'text/plain'
                    );

                $mailer->send($message);
            }

            $this->addFlash('success', "L'organisation a été confirmée.");

            return new JsonResponse(true);
        }

        return new JsonResponse(false);
    }

    private function setCompanyEnabled(Request $request, $enabled): JsonResponse
    {
        $em = $this->getDoctrine()->getManager();
        $company = $em->find(Company::class, $request->request->get('id'));

        if ($company) {
            $company->setIsActivated($enabled);
            $em->flush();
        }

        return new JsonResponse(true);
    }
}

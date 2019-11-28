<?php

/*
 * This file is part of the Kelemploi application.
 *
 * (c) Bechir Ba <bechiirr71@gmail.com>
 */

namespace App\Controller\Admin;

use App\Entity\Application;
use App\Event\ItemsEvent;
use App\Event\ItemsEvents;
use App\Form\Admin\ApplicationEditType;
use App\Repository\ApplicationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Controller to manage jobs admin pages.
 *
 * @Route("/admin/jobs")
 * @IsGranted("ROLE_ADMIN")
 *
 * @author Bechir Ba <bechiirr71@gmail.com>
 */
class ApplicationController extends AbstractController
{
    /**
     * @Route("/{page}", name="admin_jobs_list", requirements={"page"="\d+"}, defaults={"page"=1})
     */
    public function index(Request $request, int $page = 1, ApplicationRepository $jobRepository): Response
    {
        $jobs = $jobRepository->adminGetApplications($page);

        return $this->render('admin/job/list.html.twig', [
            'jobs' => $jobs,
        ]);
    }

    /**
     * @Route("/archives/{page}", name="admin_jobs_list_archived", requirements={"page"="\d+"}, defaults={"page"=1})
     */
    public function archived(Request $request, int $page = 1, ApplicationRepository $jobRepository): Response
    {
        $jobs = $jobRepository->adminGetApplications($page, true);

        return $this->render('admin/job/archived.html.twig', [
            'jobs' => $jobs,
        ]);
    }

    /**
     * @Route("/details/{job}", name="admin_job_show")
     */
    public function show(Request $request, Application $job): Response
    {
        if (!$job) {
            return $this->render('admin/pages/404.html.twig');
        }

        return $this->render('admin/job/show.html.twig', [
            'job' => $job,
            'company' => $job->getCompany(),
        ]);
    }

    /**
     * @Route("/create", name="admin_job_create")
     */
    public function create(Request $request, EntityManagerInterface $em, EventDispatcherInterface $dispatcher, UserInterface $user = null): Response
    {
        $job = new Application();
        $item = null;
        $isModifing = $request->query->has('ad-modify');

        if ($isModifing) {
            $initialType = $request->query->get('ad-type');

            switch ($initialType) {
                case 'offer':
                    $item = $em->getRepository(Offer::class)->findOneById($request->query->get('ad-id'));
                    if ($item) {
                        $job->setDeadline($item->getDeadline());
                    }

                break;
                case 'info':
                    $item = $em->getRepository(Info::class)->findOneById($request->query->get('ad-id'));

                break;
                default: break;
            }
            if ($item) {
                $job->setTitle($item->getTitle());
                $job->setCompany($item->getCompany());
                $job->setCreatedAt($item->getCreatedAt());
                $job->setDescription($item->getDescription());
            }
        }
        $form = $this->createForm(ApplicationEditType::class, $job)->remove('createdAt');

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $job->setInterlocutor($user);

            $em->persist($job);
            $em->flush();

            if ($isModifing) {
                $em->remove($item);
                $em->flush();

                $this->addFlash('success', "L'annonce a été changée.");
            } else {
                $this->addFlash('success', 'job.created');
            }

            $event = new ItemsEvent($job);
            $dispatcher->dispatch(ItemsEvents::JOB_CREATED, $event);

            return $this->redirectToRoute('admin_job_show', [
                'job' => $job->getId(),
            ]);
        }

        return $this->render('admin/job/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{slug}/edit", name="admin_job_edit")
     */
    public function edit(EntityManagerInterface $em, string $slug, Request $request): Response
    {
        $job = $em->getRepository(Application::class)->findAdminApplicationBySlug($slug);
        if (!$job) {
            throw new NotFoundHttpException();
        }
        $form = $this->createForm(ApplicationEditType::class, $job, ['job' => $job]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($job);
            $em->flush();

            $this->addFlash('success', 'edit_success');

            return $this->redirectToRoute('admin_job_show', [
                'job' => $job->getId(),
            ]);
        }

        return $this->render('admin/job/edit.html.twig', [
            'form' => $form->createView(),
            'job' => $job,
        ]);
    }

    /**
     * @Route("/disable", name="admin_job_disable", methods={"POST"})
     */
    public function disableApplication(Request $request): JsonResponse
    {
        return $this->setApplicationEnabled($request, false);
    }

    /**
     * @Route("/enable", name="admin_job_enable", methods={"POST"})
     */
    public function enableApplication(Request $request): JsonResponse
    {
        return $this->setApplicationEnabled($request, true);
    }

    /**
     * @Route("/desarchive", name="admin_job_desarchive")
     */
    public function desarchive(Request $request, EntityManagerInterface $em): Response
    {
        $job = $em->getRepository(Application::class)->findArchived($request->query->get('slug'));

        if ($job) {
            $job->setArchived(false);
            $job->setIsActivated(true);
            $em->persist($job);
            $em->flush();

            $this->addFlash('success', "L'offre a été désarchvée.");

            return $this->redirectToRoute('admin_job_show', [
                'job' => $job->getId(),
            ]);
        }

        $this->addFlash('danger', 'Page introuvable.');

        return $this->redirectToRoute('admin_index');
    }

    /**
     * @Route("/{slug}/archive", name="admin_job_archive")
     */
    public function archive(Application $job, EntityManagerInterface $em): Response
    {
        $job->setArchived(true);
        $job->setIsActivated(false);

        $em->persist($job);
        $em->flush();

        $this->addFlash('success', "L'offre a été archvée.");

        return $this->redirectToRoute('admin_job_show', [
            'job' => $job->getId(),
        ]);
    }

    /**
     * @Route("/delete/{id}", name="admin_job_delete", methods={"POST"})
     */
    public function deleteApplication(Application $job, EventDispatcherInterface $dispatcher)
    {
        if (!$job) {
            $this->addFlash('success', "L'annonce est introuvable.");
        } else {
            $em = $this->getDoctrine()->getManager();
            $em->remove($job);
            $em->flush();

            $event = new ItemsEvent($job);
            $dispatcher->dispatch(ItemsEvents::JOB_DELETED, $event);

            $this->addFlash('success', "L'annonce a été supprimée.");
        }

        return $this->redirectToRoute('admin_jobs_list');
    }

    private function setApplicationEnabled(Request $request, $enabled): JsonResponse
    {
        $em = $this->getDoctrine()->getManager();
        $job = $em->find(Application::class, $request->request->get('id'));

        if ($job) {
            $job->setIsActivated($enabled);
            if ($enabled) {
                $job->setValidatedBy($this->getUser());
            }
            $em->flush();
        }

        return new JsonResponse(true);
    }
}

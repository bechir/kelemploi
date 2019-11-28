<?php

/*
 * This file is part of the Kelemploi application.
 *
 * (c) Bechir Ba <bechiirr71@gmail.com>
 */

namespace App\Controller\Admin;

use App\Entity\Resume;
use App\Event\ResumeEvent;
use App\Event\ResumeEvents;
use App\Form\Resume\RankType;
use App\Form\ResumeType;
use App\Repository\ResumeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;
use Vich\UploaderBundle\Templating\Helper\UploaderHelper;

/**
 * Controller to manage resumes admin pages.
 *
 * @Route("/admin/resumes")
 * @IsGranted("ROLE_ADMIN")
 *
 * @author Bechir Ba <bechiirr71@gmail.com>
 */
class ResumeController extends AbstractController
{
    private $helper;

    public function __construct(UploaderHelper $helper)
    {
        $this->helper = $helper;
    }

    /**
     * @Route("/{page}", name="admin_resumes_list", requirements={"page"="\d+"}, defaults={"page"=1})
     */
    public function index(Request $request, int $page = 1, ResumeRepository $resumeRepository): Response
    {
        $resumes = $resumeRepository->adminGetResumes($page);

        return $this->render('admin/resume/list.html.twig', [
            'resumes' => $resumes,
        ]);
    }

    /**
     * @Route("/details/{resume}", name="admin_resume_show")
     */
    public function show(Request $request, Resume $resume, EntityManagerInterface $em): Response
    {
        if (!$resume) {
            return $this->render('admin/pages/404.html.twig');
        }

        // $rankForm = $this->createForm(RankType::class, $resume);

        // $rankForm->handleRequest($request);

        // if ($rankForm->isSubmitted() && $rankForm->isValid()) {
        //     $em->persist($resume);

        //     $em->flush();

        //     $this->addFlash('success', 'Le CV a été classé.');

        //     return $this->redirectToRoute('admin_resume_show', ['resume' => $resume->getId()]);
        // }

        return $this->render('admin/resume/show.html.twig', [
            'resume' => $resume,
            'user' => $resume->getUser(),
            // 'formRank' => $rankForm->createView(),
        ]);
    }

    /**
     * @Route("/download/{resume}", name="admin_resume_download")
     */
    public function download(Resume $resume): Response
    {
        if ($cv = $resume->getCv()) {
            $path = 'http://localhost:8000/public' . $this->helper->asset($cv, 'cvFile');

            return $this->file($path);
        }

        $this->addFlash('danger', 'Le fichier CV est introuvable.');

        return $this->redirectToRoute('admin_resumes_list');
    }

    /**
     * @Route("/create", name="admin_resume_create")
     */
    public function create(Request $request, EntityManagerInterface $em, EventDispatcherInterface $eventDispatcher, UserInterface $user = null): Response
    {
        $resume = new Resume();
        $form = $this->createForm(ResumeType::class, $resume);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($resume);

            $em->flush();

            $event = new ResumeEvent($resume);
            $eventDispatcher->dispatch(ResumeEvents::RESUME_CREATED, $event);

            $event = new ItemsEvent($resume);
            $eventDispatcher->dispatch(ItemsEvents::RESUME_CREATED, $event);

            $this->addFlash('success', 'resume.created');

            return $this->redirectToRoute('admin_resume_show', [
                'resume' => $resume->getId(),
            ]);
        }

        return $this->render('admin/resume/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/disable", name="admin_resume_disable", methods={"POST"})
     */
    public function disableResume(Request $request): JsonResponse
    {
        return $this->setResumeValidated($request, false);
    }

    /**
     * @Route("/enable", name="admin_resume_enable", methods={"POST"})
     */
    public function enableResume(Request $request): JsonResponse
    {
        return $this->setResumeValidated($request, true);
    }

    /**
     * @Route("/delete/{id}", name="admin_resume_delete", methods={"POST"})
     */
    public function deleteResume(Resume $resume, EventDispatcherInterface $eventDispatcher)
    {
        if (!$resume) {
            $this->addFlash('success', "L'annonce est introuvable.");
        } else {
            $em = $this->getDoctrine()->getManager();
            $em->remove($resume);
            $em->flush();

            $event = new ResumeEvent($resume);
            $eventDispatcher->dispatch(ResumeEvents::RESUME_DELETED, $event);

            $event = new ItemsEvent($resume);
            $eventDispatcher->dispatch(ItemsEvents::RESUME_DELETED, $event);

            $this->addFlash('success', "L'annonce a été supprimée.");
        }

        return $this->redirectToRoute('admin_resumes_list');
    }

    private function setResumeValidated(Request $request, $validated): JsonResponse
    {
        $em = $this->getDoctrine()->getManager();
        $resume = $em->find(Resume::class, $request->request->get('id'));

        if ($resume) {
            $resume->setValidated($validated);
            $em->flush();
        }

        return new JsonResponse(true);
    }
}

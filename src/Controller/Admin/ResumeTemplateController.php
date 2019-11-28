<?php

/*
 * This file is part of the Kelemploi application.
 *
 * (c) Bechir Ba <bechiirr71@gmail.com>
 */

namespace App\Controller\Admin;

use App\Entity\ResumeTemplate;
use App\Form\Admin\ResumeTemplateType;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Controller to manage resume templates module.
 *
 * @Route("/admin/resume-template")
 * @IsGranted("ROLE_ADMIN")
 */
class ResumeTemplateController extends AbstractController
{
    /**
     * @Route("", name="admin_resume_templates_index")
     */
    public function index(EntityManagerInterface $em)
    {
        return $this->render('admin/resume_template/index.html.twig', [
            'templates' => $em->getRepository(ResumeTemplate::class)->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="admin_resume_template_new")
     */
    public function newResumeTemplate(Request $request, EntityManagerInterface $em, UserInterface $user = null): Response
    {
        $resumeTemplate = new ResumeTemplate();
        $form = $this->createForm(ResumeTemplateType::class, $resumeTemplate);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $resumeTemplate->setAuthor($user);
            $em->persist($resumeTemplate);
            $em->flush();

            $this->addFlash('success', 'Le modèle a été créé.');

            return $this->redirectToRoute('admin_resume_template_show', [
                'slug' => $resumeTemplate->getSlug(),
            ]);
        }

        return $this->render('admin/resume_template/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{slug}", name="admin_resume_template_show")
     */
    public function showResumeTemplate(string $slug, EntityManagerInterface $em): Response
    {
        $resumeTemplate = $em->getRepository(ResumeTemplate::class)->adminFindOneBy(['slug' => $slug]);

        if (!$resumeTemplate) {
            throw new NotFoundHttpException(\sprintf('%s object not found by the @ParamConverter annotation.', ResumeTemplate::class));
        }

        return $this->render('admin/resume_template/show.html.twig', [
            'template' => $resumeTemplate,
        ]);
    }

    /**
     * @Route("/edit/{slug}", name="admin_resume_template_edit")
     */
    public function editResumeTemplate(string $slug, Request $request, EntityManagerInterface $em): Response
    {
        $resumeTemplate = $em->getRepository(ResumeTemplate::class)->adminFindOneBy(['slug' => $slug]);

        if (!$resumeTemplate) {
            throw new NotFoundHttpException(\sprintf('%s object not found by the @ParamConverter annotation.', ResumeTemplate::class));
        }
        $form = $this->createForm(ResumeTemplateType::class, $resumeTemplate);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();

            $this->addFlash('success', 'Le modèle a été modifié.');

            return $this->redirectToRoute('admin_resume_template_show', [
                'slug' => $resumeTemplate->getSlug(),
            ]);
        }

        return $this->render('admin/resume_template/edit.html.twig', [
            'form' => $form->createView(),
            'template' => $resumeTemplate,
        ]);
    }

    /**
     * @Route("/disable", name="admin_resume_template_disable", methods={"POST"})
     */
    public function disableResumeTemplate(Request $request): JsonResponse
    {
        return $this->setResumeTemplateEnabled($request, false);
    }

    /**
     * @Route("/enable", name="admin_resume_template_enable", methods={"POST"})
     */
    public function enableResumeTemplate(Request $request): JsonResponse
    {
        return $this->setResumeTemplateEnabled($request, true);
    }

    /**
     * @Route("/desarchive", name="admin_resume_template_desarchive")
     */
    public function desarchive(Request $request, EntityManagerInterface $em): Response
    {
        $resumeTemplate = $em->getRepository(ResumeTemplate::class)->findArchived($request->query->get('slug'));

        if ($resumeTemplate) {
            $resumeTemplate->setIsArchived(false);
            $resumeTemplate->setIsActivated(true);
            $em->persist($resumeTemplate);
            $em->flush();

            $this->addFlash('success', "L'offre a été désarchvée.");

            return $this->redirectToRoute('admin_resume_template_show', [
                'slug' => $resumeTemplate->getSlug(),
            ]);
        }

        $this->addFlash('danger', 'Page introuvable.');

        return $this->redirectToRoute('admin_index');
    }

    /**
     * @Route("/delete/{id}", name="admin_resume_template_delete", methods={"POST"})
     */
    public function deleteResumeTemplate(int $id, EntityManagerInterface $em)
    {
        $resumeTemplate = $em->getRepository(ResumeTemplate::class)->adminFindOneBy(['slug' => $slug]);

        if (!$resumeTemplate) {
            $this->addFlash('success', 'Le modèle est introuvable.');
        } else {
            $em = $this->getDoctrine()->getManager();
            $em->remove($resumeTemplate);
            $em->flush();

            // $event = new ItemsEvent($resumeTemplate);
            // $dispatcher->dispatch(ItemsEvents::JOB_DELETED, $event);

            $this->addFlash('success', 'Le modèle a été supprimé.');
        }

        return $this->redirectToRoute('admin_resume_templates_index');
    }

    private function setResumeTemplateEnabled(Request $request, $enabled): JsonResponse
    {
        $em = $this->getDoctrine()->getManager();
        $resumeTemplate = $em->find(ResumeTemplate::class, $request->request->get('id'));

        if ($resumeTemplate) {
            $resumeTemplate->setIsActivated($enabled);
            $em->flush();
        }

        return new JsonResponse(true);
    }
}

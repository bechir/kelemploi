<?php

/*
 * This file is part of the Kelemploi application.
 *
 * (c) Bechir Ba <bechiirr71@gmail.com>
 */

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\ResumeTemplate;
use App\Form\CommentType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\User\UserInterface;

class ResumeTemplateController extends AbstractController
{
    public function index(int $page = 1, Request $request, EntityManagerInterface $em)
    {
        $resumeTemplates = $em->getRepository(ResumeTemplate::class)->getResumeTemplates($page);

        return $this->render('resume_template/index.html.twig', [
            'templates' => $resumeTemplates,
        ]);
    }

    public function show(ResumeTemplate $resumeTemplate, Request $request, EntityManagerInterface $em, UserInterface $user = null): Response
    {
        $comment = new Comment();
        $commentForm = $this->createForm(CommentType::class, $comment);

        $commentForm->handleRequest($request);

        if ($commentForm->isSubmitted() && $commentForm->isValid()) {
            $comment->setAuthor($user);
            $resumeTemplate->addComment($comment);

            $em->persist($comment);
            $em->flush();

            $this->addFlash('success', 'Commentaire publié.');

            return new RedirectResponse($this->generateUrl('resume_template_show', [
                'slug' => $resumeTemplate->getSlug(),
            ]) . '#comment-block');
        }

        return $this->render('resume_template/show.html.twig', [
            'template' => $resumeTemplate,
            'comment_form' => $commentForm->createView(),
        ]);
    }

    public function recents(): Response
    {
        $recents = $this->getDoctrine()->getRepository(ResumeTemplate::class)->getRecents();

        return $this->render('home/recent-resume-templates.html.twig', [
            'templates' => $recents,
        ]);
    }

    public function getCommentForm(Request $request, EntityManagerInterface $em): Response
    {
        $comment = $em->getRepository(Comment::class)->findOneById($request->query->get('comment-id'));

        if ($comment) {
            $form = $this->createForm(CommentType::class, $comment);

            return $this->render('blog/blog/_form_article_comment.html.twig', [
                'form' => $form->createView(),
                'action' => $this->generateUrl('resume_template_update_comment', [
                    'id' => $comment->getId(),
                ]),
            ]);
        }
    }

    public function updateComment(Comment $comment, EntityManagerInterface $em, Request $request): Response
    {
        $em->persist($comment);
        $form = $this->createForm(CommentType::class, $comment);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();

            $this->addFlash('success', 'Commentaire modifié.');
        } else {
            $this->addFlash('danger', 'Erreur au niveua du formulaire, commentaire non modifié.');
        }

        return new RedirectResponse($this->generateUrl('resume_template_show', [
            'slug' => $comment->getResumeTemplate()->getSlug(),
        ]) . '#comment-block');
    }
}

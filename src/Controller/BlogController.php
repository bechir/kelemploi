<?php

/*
 * This file is part of the Kelemploi application.
 *
 * (C) Bechir Ba <bechiirr71@gmail.com>
 */

namespace App\Controller;

use App\Entity\Article;
use App\Entity\Comment;
use App\Form\CommentType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\User\UserInterface;

class BlogController extends AbstractController
{
    public function index(int $page = 1, Request $request, EntityManagerInterface $em)
    {
        $articles = $em->getRepository(Article::class)->getArticles($page);

        return $this->render('blog/index.html.twig', [
            'articles' => $articles,
        ]);
    }

    public function show(Article $article, Request $request, EntityManagerInterface $em, UserInterface $user = null): Response
    {
        $comment = new Comment();
        $commentForm = $this->createForm(CommentType::class, $comment);

        $commentForm->handleRequest($request);

        if($commentForm->isSubmitted() && $commentForm->isValid()) {
            $comment->setAuthor($user);
            $article->addComment($comment);

            $em->persist($comment);
            $em->flush();

            $this->addFlash('success', "Commentaire publié.");

            return new RedirectResponse($this->generateUrl('blog_article_show', [
                'slug' => $article->getSlug()
            ]) . '#comment-block' );
        }

        return $this->render('blog/show.html.twig', [
            'article' => $article,
            'comment_form' => $commentForm->createView()
        ]);
    }

    public function recentNews(): Response
    {
        $recents = $this->getDoctrine()->getRepository(Article::class)->getRecents();
        
        return $this->render('home/recent-news.html.twig', [
            'articles' => $recents
        ]);
    }

    public function getCommentForm(Request $request, EntityManagerInterface $em): Response
    {
        $comment = $em->getRepository(Comment::class)->findOneById($request->query->get('comment-id'));

        if($comment) {
            $form = $this->createForm(CommentType::class, $comment);
            return $this->render('blog/_form_article_comment.html.twig', [
                'form' => $form->createView(),
                'action' => $this->generateUrl('blog_article_update_comment', [
                    'id' => $comment->getId()
                ])
            ]);
        }
    }

    public function updateComment(Comment $comment, EntityManagerInterface $em, Request $request): Response
    {
        $em->persist($comment);
        $form = $this->createForm(CommentType::class, $comment);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            
            $this->addFlash('success', "Commentaire modifié.");
        } else {
            $this->addFlash('danger', "Erreur au niveua du formulaire, commentaire non modifié.");
        }

        return new RedirectResponse($this->generateUrl('blog_article_show', [
            'slug' => $comment->getArticle()->getSlug()
        ]) . '#comment-block' );
    }
}

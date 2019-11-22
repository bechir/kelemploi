<?php

/*
 * This file is part of the Kelemploi application.
 *
 * (c) Bechir Ba <bechiirr71@gmail.com>
 */

namespace App\Controller\Admin;

use App\Entity\Article;
use App\Form\Admin\ArticleType;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Controller to manage blog module.
 *
 * @Route("/admin/blog")
 * @IsGranted("ROLE_ADMIN")
 */
class BlogController extends AbstractController
{
    /**
     * @Route("", name="admin_blog_index")
     */
    public function index(EntityManagerInterface $em)
    {
        return $this->render('admin/blog/index.html.twig', [
            'articles' => $em->getRepository(Article::class)->findAll()
        ]);
    }

    /**
     * @Route("/article/{slug}", name="admin_blog_article_show")
     */
    public function showArticle(Article $article): Response
    {
        return $this->render('admin/blog/show.html.twig', [
            'article' => $article
        ]);
    }

    /**
     * @Route("/new", name="admin_blog_article_new")
     */
    public function newArticle(Request $request, EntityManagerInterface $em): Response
    {
        $article = new Article();
        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $em->persist($article);
            $em->flush();

            $this->addFlash('success', "L'article a été créé.");

            return $this->redirectToRoute('admin_blog_article_show', [
                'slug' => $article->getSlug()
            ]);
        }

        return $this->render('admin/blog/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/edit/{slug}", name="admin_blog_article_edit")
     */
    public function editArticle(Article $article, Request $request, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $em->flush();

            $this->addFlash('success', "L'article a été modifié.");
        }

        return $this->render('admin/blog/edit.html.twig', [
            'form' => $form->createView(),
            'article' => $article
        ]);
    }

    /**
     * @Route("/disable", name="admin_blog_article_disable", methods={"POST"})
     */
    public function disableArticle(Request $request): JsonResponse
    {
        return $this->setArticleEnabled($request, false);
    }

    /**
     * @Route("/enable", name="admin_blog_article_enable", methods={"POST"})
     */
    public function enableArticle(Request $request): JsonResponse
    {
        return $this->setArticleEnabled($request, true);
    }

    /**
     * @Route("/desarchive", name="admin_blog_article_desarchive")
     */
    public function desarchive(Request $request, EntityManagerInterface $em): Response
    {
        $article = $em->getRepository(Article::class)->findArchived($request->query->get('slug'));

        if ($article) {
            $article->setIsArchived(false);
            $article->setIsActivated(true);
            $em->persist($article);
            $em->flush();

            $this->addFlash('success', "L'offre a été désarchvée.");

            return $this->redirectToRoute('admin_blog_article_show', [
                'job' => $article->getId(),
            ]);
        }

        $this->addFlash('danger', 'Page introuvable.');

        return $this->redirectToRoute('admin_index');
    }

    /**
     * @Route("/{slug}/archive", name="admin_blog_article_archive")
     */
    public function archive(Article $article, EntityManagerInterface $em): Response
    {
        $article->setIsArchived(true);
        $article->setIsActivated(false);

        $em->persist($article);
        $em->flush();

        $this->addFlash('success', "L'offre a été archvée.");

        return $this->redirectToRoute('admin_blog_article_show', [
            'job' => $article->getId(),
        ]);
    }

    /**
     * @Route("/delete/{id}", name="admin_blog_article_delete", methods={"POST"})
     */
    public function deleteArticle(Article $article, EventDispatcherInterface $dispatcher)
    {
        if (!$article) {
            $this->addFlash('success', "L'annonce est introuvable.");
        } else {
            $em = $this->getDoctrine()->getManager();
            $em->remove($article);
            $em->flush();

            // $event = new ItemsEvent($article);
            // $dispatcher->dispatch(ItemsEvents::JOB_DELETED, $event);

            $this->addFlash('success', "L'annonce a été supprimée.");
        }

        return $this->redirectToRoute('admin_blog_index');
    }

    private function setArticleEnabled(Request $request, $enabled): JsonResponse
    {
        $em = $this->getDoctrine()->getManager();
        $article = $em->find(Article::class, $request->request->get('id'));

        if ($article) {
            $article->setIsActivated($enabled);
            $em->flush();
        }

        return new JsonResponse(true);
    }
}

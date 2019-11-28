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
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;

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
            'articles' => $em->getRepository(Article::class)->findAll(),
        ]);
    }

    /**
     * @Route("/{slug}", name="admin_blog_article_show")
     */
    public function showArticle(string $slug, EntityManagerInterface $em): Response
    {
        $article = $em->getRepository(Article::class)->adminFindOneBy(['slug' => $slug]);

        if (!$article) {
            throw new NotFoundHttpException(\sprintf('%s object not found by the @ParamConverter annotation.', Article::class));
        }

        return $this->render('admin/blog/show.html.twig', [
            'article' => $article,
        ]);
    }

    /**
     * @Route("/new", name="admin_blog_article_new")
     */
    public function newArticle(Request $request, EntityManagerInterface $em, UserInterface $user = null): Response
    {
        $article = new Article();
        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user->addArticle($article);
            $em->persist($article);
            $em->flush();

            $this->addFlash('success', "L'article a été créé.");

            return $this->redirectToRoute('admin_blog_article_show', [
                'slug' => $article->getSlug(),
            ]);
        }

        return $this->render('admin/blog/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/edit/{slug}", name="admin_blog_article_edit")
     */
    public function editArticle(string $slug, Request $request, EntityManagerInterface $em): Response
    {
        $article = $em->getRepository(Article::class)->adminFindOneBy(['slug' => $slug]);

        if (!$article) {
            throw new NotFoundHttpException(\sprintf('%s object not found by the @ParamConverter annotation.', Article::class));
        }
        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();

            $this->addFlash('success', "L'article a été modifié.");

            return $this->redirectToRoute('admin_blog_article_show', [
                'slug' => $article->getSlug(),
            ]);
        }

        return $this->render('admin/blog/edit.html.twig', [
            'form' => $form->createView(),
            'article' => $article,
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
                'slug' => $article->getSlug(),
            ]);
        }

        $this->addFlash('danger', 'Page introuvable.');

        return $this->redirectToRoute('admin_index');
    }

    /**
     * @Route("/{slug}/archive", name="admin_blog_article_archive")
     */
    public function archive(string $slug, EntityManagerInterface $em): Response
    {
        $article = $em->getRepository(Article::class)->adminFindOneBy(['slug' => $slug]);

        if (!$article) {
            throw new NotFoundHttpException(\sprintf('%s object not found by the @ParamConverter annotation.', Article::class));
        }
        $article->setIsArchived(true);
        $article->setIsActivated(false);

        $em->persist($article);
        $em->flush();

        $this->addFlash('success', "L'article a été archvé.");

        return $this->redirectToRoute('admin_blog_index');
    }

    /**
     * @Route("/delete/{id}", name="admin_blog_article_delete", methods={"POST"})
     */
    public function deleteArticle(int $id, EventDispatcherInterface $dispatcher)
    {
        $article = $em->getRepository(Article::class)->adminFindOneBy(['slug' => $slug]);

        if (!$article) {
            $this->addFlash('success', "L'article est introuvable.");
        } else {
            $em = $this->getDoctrine()->getManager();
            $em->remove($article);
            $em->flush();

            // $event = new ItemsEvent($article);
            // $dispatcher->dispatch(ItemsEvents::JOB_DELETED, $event);

            $this->addFlash('success', "L'article a été supprimé.");
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

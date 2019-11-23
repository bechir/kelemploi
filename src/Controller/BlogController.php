<?php

/*
 * This file is part of the Kelemploi application.
 *
 * (C) Bechir Ba <bechiirr71@gmail.com>
 */

namespace App\Controller;

use App\Entity\Article;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class BlogController extends AbstractController
{
    public function index(int $page = 1, Request $request, EntityManagerInterface $em)
    {
        $articles = $em->getRepository(Article::class)->getArticles($page);

        return $this->render('blog/index.html.twig', [
            'articles' => $articles,
        ]);
    }

    public function show(Article $article): Response
    {
        return $this->render('blog/show.html.twig', [
            'article' => $article
        ]);
    }
}

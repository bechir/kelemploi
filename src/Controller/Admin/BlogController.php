<?php

/*
 * This file is part of the Kelemploi application.
 *
 * (c) Bechir Ba <bechiirr71@gmail.com>
 */

namespace App\Controller\Admin;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
    public function index()
    {
        return $this->render('admin/blog/index.html.twig', [
        ]);
    }
}

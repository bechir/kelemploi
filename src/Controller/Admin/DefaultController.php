<?php

/*
 * This file is part of the Beta application.
 *
 * (c) Bechir Ba <bechiirr71@gmail.com>
 */

namespace App\Controller\Admin;

use App\Entity\Company;
use App\Entity\Application;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Controller to manage default admin pages.
 *
 * @Route("/admin")
 * @IsGranted("ROLE_ADMIN")
 *
 * @author Bechir Ba <bechiirr71@gmail.com>
 */
class DefaultController extends AbstractController
{
    /**
     * @Route("", name="admin_index")
     * @Route("", name="admin_index")
     */
    public function index(Request $request, EntityManagerInterface $em): Response
    {
        $jobs = $em->getRepository(Application::class)->adminGetRecents();
        $users = $em->getRepository(User::class)->adminGetRecents();

        return $this->render('admin/pages/index.html.twig', [
            'data' => $this->getStats(),
            'jobs' => $jobs,
            'users' => $users,
        ]);
    }

    /**
     * @Route("/search", name="admin_search")
     */
    public function search(Request $request, EntityManagerInterface $em): Response
    {
        $type = $request->query->get('type');
        $terms = $request->query->get('terms');
        $results = null;

        if (!in_array($type, ['job', 'user', 'company'])) {
            $this->addFlash('info', "Le de resultat est inconnu.");
            return $this->redirectToRoute('admin_index');
        }

        switch($type) {
            case 'job':
                $results = $em->getRepository(Application::class)->findBySearchTerms($terms);
            break;
            case 'user':
                $results = $em->getRepository(User::class)->findBySearchTerms($terms);
            break;
            case 'company':
                $results = $em->getRepository(Company::class)->findBySearchTerms($terms);
            break;
            default: break;
        }

        return $this->render("admin/search/index.html.twig", [
            'type' => $type,
            'results' => $results
        ]);
    }

    /**
     * @Route("/pendings", name="admin_pendings")
     */
    public function pendings(EntityManagerInterface $em): Response
    {
        $jobs = $em->getRepository(Application::class)->getPendings();

        return $this->render('admin/pendings.html.twig', [
            'jobs' => $jobs,
            'count' => count($jobs),
        ]);
    }

    /**
     * @Route("/basic-usage", name="admin_basic_usage")
     */
    public function basicUsage(Request $request): Response
    {
        return $this->render('admin/pages/basicUsage.html.twig');
    }

    /**
     * @Route("/security", name="admin_security")
     */
    public function security(Request $request): Response
    {
        return $this->render('admin/pages/security.html.twig');
    }

    /**
     * @Route("/troubleshooting", name="admin_troubleshooting")
     */
    public function troubleshooting(Request $request): Response
    {
        return $this->render('admin/pages/troubleshooting.html.twig');
    }

    /**
     * Profile page.
     *
     * @Route("/profile", name="admin_profile")
     */
    public function profile(Request $request): Response
    {
        return $this->show($request, $this->getUser());
    }

    /**
     * @Route("/users", name="admin_users")
     */
    public function users(Request $request): Response
    {
        $list = $this->getDoctrine()->getRepository(User::class)->getUsers(1);

        return $this->render('admin/user/list.html.twig', [
          'users' => $list,
        ]);
    }

    /**
     *  List users pagination.
     *
     * @Route("/users/page/{page}", name="admin_users_paginated", requirements={"page"="\d+"})
     */
    public function usersPaginate(Request $request, $page): Response
    {
        $list = $this->getDoctrine()->getRepository(User::class)->getUsers($page);

        return $this->render('admin/user/list.html.twig', [
          'users' => $list,
        ]);
    }

    /**
     * @Route("/stats", name="admin_stats")
     */
    public function stats(Request $request): Response
    {
        return $this->render('admin/pages/stats.html.twig');
    }

    /**
     * @Route("/notifications", name="admin_notifications")
     */
    public function notifications(Request $request): Response
    {
        return $this->render('admin/pages/notifications.html.twig');
    }

    /**
     * @Route("/user/{user}", name="admin_user_show")
     */
    public function show(Request $request, User $user): Response
    {
        if (!$user) {
            return $this->render('admin/pages/404.html.twig');
        }

        return $this->render('admin/user/show.html.twig', [
            'user' => $user,
        ]);
    }

    /**
     * @Route("/demo/{name}", name="admin_page_demo")
     * @IsGranted("ROLE_SUPER_ADMIN")
     */
    public function pageDemo($name): Response
    {
        return $this->render("admin/demo/$name.html.twig");
    }

    public function getStats()
    {
        $em = $this->getDoctrine()->getManager();

        $qb = $em->createQueryBuilder();

        $users = $qb->select('count(u.id)')->from('App:User', 'u')
            ->getQuery()->getSingleScalarResult();

        $ads =
            $em->createQueryBuilder('j')->select('count(j.id)')
                ->from('App:Application', 'j')
                ->getQuery()->getSingleScalarResult();

        $resumes = $em->createQueryBuilder('r')->select('count(r.id)')
            ->from('App:Resume', 'r')
            ->getQuery()->getSingleScalarResult();

        $companies = $em->createQueryBuilder('c')->select('count(c.id)')
            ->from('App:Company', 'c')
            ->getQuery()->getSingleScalarResult();

        return [
            'ads' => $ads,
            'users' => $users,
            'resumes' => $resumes,
            'companies' => $companies,
        ];
    }
}

<?php

namespace App\Controller\Admin;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

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
    public function index(Request $request): Response
    {
        return $this->render('admin/pages/index.html.twig', [
            'data' => $this->getStats()
        ]);
    }

    /**
     * @Route("/basic-usage", name="admin_basic_usage")
     */
    public function basicUsage(Request $request) : Response
    {
        return $this->render('admin/pages/basicUsage.html.twig');
    }

    /**
     * @Route("/security", name="admin_security")
     */
    public function security(Request $request) : Response
    {
        return $this->render('admin/pages/security.html.twig');
    }

    /**
     * @Route("/troubleshooting", name="admin_troubleshooting")
     */
    public function troubleshooting(Request $request) : Response
    {
        return $this->render('admin/pages/troubleshooting.html.twig');
    }

    /**
     * Profile page
     *
     * @Route("/profile", name="admin_profile")
     */
    public function profile(Request $request) : Response
    {
        return $this->show($request, $this->getUser());
    }

    /**
     * @Route("/users", name="admin_users")
     */
    public function users(Request $request) : Response
    {
        $list = $this->getDoctrine()->getRepository(User::class)->getUsers(1);
        return $this->render('admin/user/list.html.twig', [
          'users' => $list,
        ]);
    }

    /**
     *  List users pagination
     *
     * @Route("/users/page/{page}", name="admin_users_paginated", requirements={"page"="\d+"})
     */
    public function usersPaginate(Request $request, $page) : Response
    {
        $list = $this->getDoctrine()->getRepository(User::class)->getUsers($page);
        return $this->render('admin/user/list.html.twig', [
          'users' => $list,
        ]);
    }

    /**
     * @Route("/stats", name="admin_stats")
     */
    public function stats(Request $request) : Response
    {
        return $this->render('admin/pages/stats.html.twig');
    }

    /**
     * @Route("/notifications", name="admin_notifications")
     */
    public function notifications(Request $request) : Response
    {
        return $this->render('admin/pages/notifications.html.twig');
    }

    /**
     * @Route("/settings", name="admin_settings")
     */
    public function settings(Request $request) : Response
    {
        return $this->render('admin/pages/settings.html.twig');
    }

    /**
     * @Route("/user/{user}", name="admin_user_show")
     */
    public function show(Request $request, User $user) : Response
    {
        if (!$user) {
            return $this->render('admin/pages/404.html.twig');
        }
        return $this->render('admin/user/show.html.twig', [
            'user' => $user
        ]);
    }

    /**
     * @Route("/demo/{name}", name="admin_page_demo")
     * @IsGranted("ROLE_SUPER_ADMIN")
     */
    public function pageDemo($name) : Response
    {
        return $this->render("admin/demo/$name.html.twig");
    }

    public function getStats()
    {
        $em = $this->getDoctrine()->getManager();


        $qb = $em->createQueryBuilder();

        $activeUsers = $qb->select('count(u.id)')
            ->from('App:User', 'u')
            ->where($qb->expr()->eq('u.enabled', 'true'))
            ->getQuery()
            ->getSingleScalarResult();

        $purchasesCount = 112;
        
        return [
            'activeUsers'   => $activeUsers,
            'purchasesCount'         => $purchasesCount
        ];
    }
}

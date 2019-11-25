<?php

/*
 * This file is part of the Kelemploi application.
 *
 * (c) Bechir Ba <bechiirr71@gmail.com>
 */

namespace App\Controller\Admin;

use App\Entity\Contest;
use App\Form\Admin\ContestType;
use App\Repository\ContestRepository;
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
 * Controller to manage contests admin pages.
 *
 * @Route("/admin/contests")
 * @IsGranted("ROLE_ADMIN")
 *
 * @author Bechir Ba <bechiirr71@gmail.com>
 */
class ContestController extends AbstractController
{
    /**
     * @Route("/{page}", name="admin_contests_list", requirements={"page"="\d+"}, defaults={"page"=1})
     */
    public function index(Request $request, int $page = 1, ContestRepository $contestRepository): Response
    {
        $contests = $contestRepository->adminGetContests($page);

        return $this->render('admin/contest/list.html.twig', [
            'contests' => $contests,
        ]);
    }

    /**
     * @Route("/archives/{page}", name="admin_contests_list_archived", requirements={"page"="\d+"}, defaults={"page"=1})
     */
    public function archived(Request $request, int $page = 1, ContestRepository $contestRepository): Response
    {
        $contests = $contestRepository->adminGetContests($page, true);

        return $this->render('admin/contest/archived.html.twig', [
            'contests' => $contests,
        ]);
    }

    /**
     * @Route("/details/{slug}", name="admin_contest_show")
     */
    public function show(Request $request, Contest $contest): Response
    {
        if (!$contest) {
            return $this->render('admin/pages/404.html.twig');
        }

        return $this->render('admin/contest/show.html.twig', [
            'contest' => $contest,
        ]);
    }

    /**
     * @Route("/create", name="admin_contest_create")
     */
    public function create(Request $request, EntityManagerInterface $em, EventDispatcherInterface $dispatcher, UserInterface $user = null): Response
    {
        $contest = new Contest();
        $form = $this->createForm(ContestType::class, $contest);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $contest->setAuthor($user);

            $em->persist($contest);
            $em->flush();

            $this->addFlash('success', "L'annonce a été créé.");

            return $this->redirectToRoute('admin_contest_show', [
                'slug' => $contest->getSlug(),
            ]);
        }

        return $this->render('admin/contest/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{slug}/edit", name="admin_contest_edit")
     */
    public function edit(EntityManagerInterface $em, string $slug, Request $request): Response
    {
        $contest = $em->getRepository(Contest::class)->findAdminContestBySlug($slug);
        if (!$contest) {
            throw new NotFoundHttpException();
        }
        $form = $this->createForm(ContestType::class, $contest);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($contest);
            $em->flush();

            $this->addFlash('success', 'edit_success');

            return $this->redirectToRoute('admin_contest_show', [
                'slug' => $contest->getSlug(),
            ]);
        }

        return $this->render('admin/contest/edit.html.twig', [
            'form' => $form->createView(),
            'contest' => $contest,
        ]);
    }

    /**
     * @Route("/disable", name="admin_contest_disable", methods={"POST"})
     */
    public function disableContest(Request $request): JsonResponse
    {
        return $this->setContestEnabled($request, false);
    }

    /**
     * @Route("/enable", name="admin_contest_enable", methods={"POST"})
     */
    public function enableContest(Request $request): JsonResponse
    {
        return $this->setContestEnabled($request, true);
    }

    /**
     * @Route("/desarchive", name="admin_contest_desarchive")
     */
    public function desarchive(Request $request, EntityManagerInterface $em): Response
    {
        $contest = $em->getRepository(Contest::class)->findArchived($request->query->get('slug'));

        if ($contest) {
            $contest->setArchived(false);
            $contest->setActivated(true);
            $em->persist($contest);
            $em->flush();

            $this->addFlash('success', "L'offre a été désarchvée.");

            return $this->redirectToRoute('admin_contest_show', [
                'slug' => $contest->getSlug(),
            ]);
        }

        $this->addFlash('danger', 'Page introuvable.');

        return $this->redirectToRoute('admin_index');
    }

    /**
     * @Route("/{slug}/archive", name="admin_contest_archive")
     */
    public function archive(Contest $contest, EntityManagerInterface $em): Response
    {
        $contest->setArchived(true);
        $contest->setActivated(false);

        $em->persist($contest);
        $em->flush();

        $this->addFlash('success', "L'offre a été archvée.");

        return $this->redirectToRoute('admin_contest_show', [
            'slug' => $contest->getSlug(),
        ]);
    }

    /**
     * @Route("/delete/{id}", name="admin_contest_delete", methods={"POST"})
     */
    public function deleteContest(Contest $contest, EventDispatcherInterface $dispatcher)
    {
        if (!$contest) {
            $this->addFlash('success', "L'annonce est introuvable.");
        } else {
            $em = $this->getDoctrine()->getManager();
            $em->remove($contest);
            $em->flush();

            $this->addFlash('success', "L'annonce a été supprimée.");
        }

        return $this->redirectToRoute('admin_contests_list');
    }

    private function setContestEnabled(Request $request, $enabled): JsonResponse
    {
        $em = $this->getDoctrine()->getManager();
        $contest = $em->find(Contest::class, $request->request->get('id'));

        if ($contest) {
            $contest->setActivated($enabled);
            $em->flush();
        }

        return new JsonResponse(true);
    }
}

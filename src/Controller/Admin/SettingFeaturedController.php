<?php

/*
 * This file is part of the Kelemploi application.
 *
 * (c) Bechir Ba <bechiirr71@gmail.com>
 */

namespace App\Controller\Admin;

use App\Entity\Application;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @IsGranted("ROLE_ADMIN")
 * @Route("/admin/settings/featured")
 */
class SettingFeaturedController extends AbstractController
{
    /**
     * @Route("", name="admin_settings_featured")
     */
    public function index(EntityManagerInterface $em): Response
    {
        return $this->render('admin/settings/featured/index.html.twig', [
            'active' => 'featured',
            'jobs' => $em->getRepository(Application::class)->findBy(['isFeatured' => true]),
        ]);
    }

    /**
     * @Route("/search", name="admin_settings_search_jobs")
     */
    public function searchJob(Request $request, EntityManagerInterface $em): Response
    {
        $terms = $request->query->get('terms');
        $jobs = $em->getRepository(Application::class)->findBySearchTerms($terms);

        return $this->render('admin/settings/featured/search-result.html.twig', [
            'jobs' => $jobs,
        ]);
    }

    /**
     * @Route("/add", name="admin_settings_featured_new")
     */
    public function addToFeatureds(Request $request, EntityManagerInterface $em): JsonResponse
    {
        $jobId = $request->query->get('jobId');

        $job = $em->getRepository(Application::class)->findOneById($jobId);

        if ($job && !$job->isFeatured()) {
            $job->setIsFeatured(true);
            $em->flush();

            return new JsonResponse([
                'code' => 200,
                'message' => "L'offre a été ajouté.",
            ]);
        }

        return new JsonResponse([
            'code' => 404,
            'message' => "L'offre est introuvable.",
        ]);
    }

    /**
     * @Route("/{job}/remove-from-featureds", name="admin_settings_remove_from_featureds")
     */
    public function removeFromFeatured(Application $job, EntityManagerInterface $em): Response
    {
        if ($job->isFeatured()) {
            $job->setIsFeatured(false);
            $em->flush();
            $this->addFlash('success', "L'offre a été retiré.");
        }

        return $this->redirectToRoute('admin_settings_featured');
    }
}

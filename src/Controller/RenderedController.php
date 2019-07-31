<?php

/*
 * This file is part of the Kelemploi application.
 *
 * (C) Bechir Ba <bechiirr71@gmail.com>
 */

namespace App\Controller;

use App\Entity\Region;
use Symfony\Bundle\FrameworkBundle\Controller\Controller as Controller;
use Symfony\Component\HttpFoundation\Response;
use App\Entity\Application;

class RenderedController extends Controller
{
    public function recentJobs(): Response
    {
        $list = $this->getDoctrine()->getRepository(Application::class)->getLatest();

        return $this->render('application/jobs-items.html.twig', [
            'list' => $list,
            'class' => 'col'
        ]);
    }

    public function similarJobs($category): Response
    {
        $list = $this->getDoctrine()->getRepository(Application::class)->findByJobCategory($category);

        return $this->render('application/jobs-items.html.twig', [
            'list' => $list
        ]);
    }

    public function statistics(): Response
    {
        return $this->render('rendered/statistics.html.twig', [
        ]);
    }

    public function regionsListing(): Response
    {
        $regions = $this->getDoctrine()->getRepository(Region::class)->findAll();
        $counts = [];

        foreach ($regions as $region) {
            $counts[$region->getSlug()] = rand(1, 11) * 3 + rand(0, 233);
        }

        return $this->render('rendered/regions-listing.html.twig', [
            'regions' => $regions,
            'counts' => $counts,
        ]);
    }
}

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

class RenderedController extends Controller
{
    public function index(): Response
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

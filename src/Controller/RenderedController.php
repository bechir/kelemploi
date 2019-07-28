<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller as Controller;
use Symfony\Component\HttpFoundation\Response;
use App\Entity\Region;

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
            'counts' => $counts
        ]);
    }
}
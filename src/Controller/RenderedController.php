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

        $em = $this->getDoctrine()->getManager();
        $q =  $em->createQueryBuilder();
        
        $counts = $q->select('count(a.id), r.slug', 'a')
            ->from('App:Application', 'a')
            ->leftJoin('a.company', 'c')
                ->addSelect('c')
            ->leftJoin('c.region', 'r')
                ->addSelect('r')
            ->groupBy('r.id')
            ->getQuery()
            ->getArrayResult();

        $keysValues = [];
        foreach ($counts as $c) {
            $keysValues[$c['slug']] = $c[1];
        }

        return $this->render('rendered/regions-listing.html.twig', [
            'regions' => $regions,
            'counts' => $keysValues,
        ]);
    }
}

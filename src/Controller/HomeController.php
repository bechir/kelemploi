<?php

/*
 * This file is part of the Kelemploi application.
 *
 * (C) Bechir Ba <bechiirr71@gmail.com>
 */

namespace App\Controller;

use App\Entity\JobCategory;
use App\Entity\Region;
use App\Form\ContactType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller as Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class HomeController extends Controller
{
    public function index(): Response
    {
        $em = $this->getDoctrine()->getManager();
        $regions = $em->getRepository(Region::class)->findAll();
        $categories = $em->getRepository(JobCategory::class)->findAll();

        return $this->render('home/index.html.twig', [
            'regions' => $regions,
            'categories' => $categories,
        ]);
    }

    public function frequentSearch(): Response
    {
        return $this->render('home/frequent-search.html.twig');
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

        return $this->render('home/regions-listing.html.twig', [
            'regions' => $regions,
            'counts' => $keysValues,
        ]);
    }

    public function statistics(): Response
    {
        return $this->render('home/statistics.html.twig');
    }

}

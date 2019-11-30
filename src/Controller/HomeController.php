<?php

/*
 * This file is part of the Kelemploi application.
 *
 * (c) Bechir Ba <bechiirr71@gmail.com>
 */

namespace App\Controller;

use App\Entity\Industry;
use App\Entity\JobCategory;
use App\Entity\Region;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController as Controller;
use Symfony\Component\HttpFoundation\Response;

class HomeController extends Controller
{
    public function index(EntityManagerInterface $em): Response
    {
        $regions = $em->getRepository(Region::class)->findAll();
        $categories = $em->getRepository(JobCategory::class)->findAll();

        return $this->render('home/index.html.twig', [
            'regions' => $regions,
            'categories' => $categories,
        ]);
    }

    public function frequentSearch(EntityManagerInterface $em): Response
    {
        $regions = $em->getRepository(Region::class)->findAll();
        $jobCategories = $em->getRepository(JobCategory::class)->findAll();
        $industries = $em->getRepository(Industry::class)->findAll();

        $em = $this->getDoctrine()->getManager();
        $q = $em->createQueryBuilder();

        $regionCounts = $q->select('count(a.id), r.slug as region_slug', 'a')
            ->from('App:Application', 'a')
            ->leftJoin('a.company', 'c')
                ->addSelect('c')
            ->leftJoin('c.region', 'r')
                ->addSelect('r')
            ->groupBy('r.name')
            ->where('a.isActivated = true')
            ->getQuery()
            ->getArrayResult();

        $categoryCounts = $em->createQueryBuilder()
            ->select('count(a.id), c.slug as category_slug', 'a')
            ->from('App:Application', 'a')
            ->leftJoin('a.postCategory', 'c')
                ->addSelect('c')
            ->where('a.isActivated = true')
            ->groupBy('c.name')
            ->getQuery()
            ->getArrayResult();

        $industryCounts = $em->createQueryBuilder()
            ->select('count(a.id), i.slug as industry_slug', 'a')
            ->from('App:Application', 'a')
            ->leftJoin('a.company', 'c')
                ->addSelect('c')
            ->leftJoin('c.industry', 'i')
                ->addSelect('i')
            ->where('a.isActivated = true')
            ->groupBy('i.name')
            ->getQuery()
            ->getArrayResult();

        $keysValuesRegion = [];
        $keysValuesJobCategory = [];
        $keysValuesIndustry = [];
        foreach ($regionCounts as $c) {
            $keysValuesRegion[$c['region_slug']] = $c[1];
        }
        foreach ($categoryCounts as $c) {
            $keysValuesJobCategory[$c['category_slug']] = $c[1];
        }
        foreach ($industryCounts as $c) {
            $keysValuesIndustry[$c['industry_slug']] = $c[1];
        }

        return $this->render('home/frequent-search.html.twig', [
            'regions' => $regions,
            'region_counts' => $keysValuesRegion,
            'job_categories' => $jobCategories,
            'job_category_counts' => $keysValuesJobCategory,
            'industries' => $industries,
            'industry_counts' => $keysValuesIndustry,
        ]);
    }

    public function regionsListing(EntityManagerInterface $em): Response
    {
        $regions = $em->getRepository(Region::class)->findAll();

        $q = $em->createQueryBuilder();

        $counts = $q->select('count(a.id) as count, r.slug', 'a')
            ->from('App:Application', 'a')
            ->leftJoin('a.company', 'c')
                ->addSelect('c')
            ->leftJoin('c.region', 'r')
                ->addSelect('r')
            ->where('a.isActivated = true')
            ->groupBy('r.id')
            ->getQuery()
            ->getArrayResult();

        $keysValues = [];
        foreach ($counts as $c) {
            $keysValues[$c['slug']] = $c['count'];
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

<?php

/*
 * This file is part of the Kelemploi application.
 *
 * (C) Bechir Ba <bechiirr71@gmail.com>
 */

namespace App\Repository;

use App\Entity\Application;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Pagerfanta\Pagerfanta;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Application|null find($id, $lockMode = null, $lockVersion = null)
 * @method Application|null findOneBy(array $criteria, array $orderBy = null)
 * @method Application[]    findAll()
 * @method Application[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ApplicationRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Application::class);
    }

    public function getLatest()
    {
        return $this->createQueryBuilder('a')
            ->leftJoin('a.company', 'c')
              ->addSelect('c')
            ->orderBy('a.id', 'DESC')
            ->getQuery()
            ->setMaxResults(Application::NB_IMTEMS_HOME)
      ->getResult();
    }

    public function findByJobCategory($category)
    {
        return $this->createQueryBuilder('a')
            ->leftJoin('a.company', 'c')
                ->addSelect('c')
            ->leftJoin('a.postCategory', 'p')
                ->addSelect('p')
            ->where('p.name = :name')
            ->setParameter('name', $category)
            ->getQuery()
            ->setMaxResults(Application::NB_IMTEMS_HOME)
        ->getResult();
    }

    public function getApps(int $page = 1): Pagerfanta
    {
        $qb = $this->createQueryBuilder('a')
            ->leftJoin('a.company', 'c')
                ->addSelect('c')
            ->leftJoin('c.region', 'r')
                ->addSelect('r')
            ->leftJoin('a.postCategory', 'pc')
                ->addSelect('pc')
            ->leftJoin('a.dates', 'd')
                ->addSelect('d')
            ->orderBy('d.start', 'DESC')
        ;

        return $this->createPaginator($qb->getQuery(), $page);
    }

    public function findBySearchQuery(array $search, int $page = 1): ?Pagerfanta
    {
        $queryBuilder = $this
            ->createQueryBuilder('a')
            ->leftJoin('a.company', 'c')
                ->addSelect('c')
            ->leftJoin('c.region', 'r')
                ->addSelect('r')
            ->leftJoin('a.postCategory', 'pc')
                ->addSelect('pc')
            ->leftJoin('a.dates', 'd')
                ->addSelect('d')
            ->orderBy('d.start', 'DESC')
        ;

        if(!empty($search['region'])) {
            $queryBuilder->andWhere('r.slug = :region')
                ->setParameter('region', $search['region']);
        }

        if(!empty($search['category'])) {
            $queryBuilder->andWhere('pc.slug = :category')
                ->setParameter('category', $search['category']);
        }

        return $this->createPaginator($queryBuilder->getQuery(), $page);
    }

    /**
     * @return Application[]
     */
    public function createPaginator(Query $query, int $page, $isAdmin = false): Pagerfanta
    {
        $paginator = new Pagerfanta(new DoctrineORMAdapter(($query)));
        if($isAdmin) {
            $paginator->setMaxPerPage(Application::NB_ITEMS_ADMIN_LISTING);
        }else {
            $paginator->setMaxPerPage(Application::NB_ITEMS_LISTING);
        }
        $paginator->setCurrentPage($page);

        return $paginator;
    }
}

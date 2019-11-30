<?php

/*
 * This file is part of the Kelemploi application.
 *
 * (c) Bechir Ba <bechiirr71@gmail.com>
 */

namespace App\Repository;

use App\Entity\Company;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Pagerfanta\Pagerfanta;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Company|null find($id, $lockMode = null, $lockVersion = null)
 * @method Company|null findOneBy(array $criteria, array $orderBy = null)
 * @method Company[]    findAll()
 * @method Company[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CompanyRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Company::class);
    }

    public function getCompanies(int $page = 1): Pagerfanta
    {
        $qb = $this->createQueryBuilder('c')
            ->leftJoin('c.industry', 'i')
                ->addSelect('i')
            ->leftJoin('c.region', 'r')
                ->addSelect('r')
            ->leftJoin('c.photo', 'p')
                ->addSelect('p');

        return $this->createPaginator($qb->getQuery(), $page);
    }

    public function adminGetCompanies(int $page = 1): Pagerfanta
    {
        $qb = $this->createQueryBuilder('c')
            ->leftJoin('c.industry', 'i')
                ->addSelect('i')
            ->leftJoin('c.region', 'r')
                ->addSelect('r')
            ->leftJoin('c.photo', 'p')
                ->addSelect('p')
            ->orderBy('c.confirmed', 'ASC');

        return $this->createPaginator($qb->getQuery(), $page, true);
    }

    /**
     * @return Company[]
     */
    public function createPaginator(Query $query, int $page, $isAdmin = false): Pagerfanta
    {
        $paginator = new Pagerfanta(new DoctrineORMAdapter(($query)));
        if ($isAdmin) {
            $paginator->setMaxPerPage(Company::NB_ITEMS_ADMIN_LISTING);
        } else {
            $paginator->setMaxPerPage(Company::NB_ITEMS_LISTING);
        }
        $paginator->setCurrentPage($page);

        return $paginator;
    }
}

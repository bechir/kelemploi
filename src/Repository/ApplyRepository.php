<?php

/*
 * This file is part of the Kelemploi application.
 *
 * (c) Bechir Ba <bechiirr71@gmail.com>
 */

namespace App\Repository;

use App\Entity\Application as Job;
use App\Entity\Apply;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Pagerfanta\Pagerfanta;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Apply|null find($id, $lockMode = null, $lockVersion = null)
 * @method Apply|null findOneBy(array $criteria, array $orderBy = null)
 * @method Apply[]    findAll()
 * @method Apply[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ApplyRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Apply::class);
    }

    public function paginateByJob(Job $job, int $page): Pagerfanta
    {
        $qb = $this->createQueryBuilder('a')
            ->leftJoin('a.job', 'j')
                ->addSelect('j')
            ->leftJoin('a.candidate', 'c')
                ->addSelect('c')
            ->where('j = :job')
            ->setParameter('job', $job)
            ->orderBy('a.appliedAt', 'DESC')
        ;

        return $this->createPaginator($qb->getQuery(), $page);
    }

    /**
     * @return Apply[]
     */
    public function createPaginator(Query $query, int $page, $isAdmin = false): Pagerfanta
    {
        $paginator = new Pagerfanta(new DoctrineORMAdapter(($query)));
        if ($isAdmin) {
            $paginator->setMaxPerPage(Apply::NB_ITEMS_ADMIN_LISTING);
        } else {
            $paginator->setMaxPerPage(Apply::NB_ITEMS_LISTING);
        }
        $paginator->setCurrentPage($page);

        return $paginator;
    }
}

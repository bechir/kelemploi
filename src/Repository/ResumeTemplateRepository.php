<?php

/*
 * This file is part of the Kelemploi application.
 *
 * (c) Bechir Ba <bechiirr71@gmail.com>
 */

namespace App\Repository;

use App\Entity\ResumeTemplate;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Pagerfanta\Pagerfanta;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method ResumeTemplate|null find($id, $lockMode = null, $lockVersion = null)
 * @method ResumeTemplate|null findOneBy(array $criteria, array $orderBy = null)
 * @method ResumeTemplate[]    findAll()
 * @method ResumeTemplate[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ResumeTemplateRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ResumeTemplate::class);
    }

    public function findOneBy(array $criteria, array $orderBy = null): ?ResumeTemplate
    {
        $qb = $this->createQueryBuilder('rt')
            ->leftJoin('rt.coverImage', 'c')
                ->addSelect('c')
            ->andWhere('rt.isActivated = true');

        if (isset($criteria['slug'])) {
            $qb->andWhere('rt.slug = :slug')
            ->setParameter('slug', $criteria['slug']);
        }

        if (isset($criteria['id'])) {
            $qb->andWhere('rt.id = :id')
            ->setParameter('id', $criteria['id']);
        }

        return $qb->getQuery()->getOneOrNullResult();
    }

    public function getRecents()
    {
        return $this->createQueryBuilder('rt')
            ->leftJoin('rt.coverImage', 'c')
                ->addSelect('c')
            ->andWhere('rt.isActivated = true')
            ->orderBy('rt.updatedAt', 'DESC')
            ->setMaxResults(ResumeTemplate::NB_ITEMS_HOME)
            ->getQuery()
            ->getResult()
        ;
    }

    public function adminFindOneBy(array $criteria, array $orderBy = null): ?ResumeTemplate
    {
        $qb = $this->createQueryBuilder('rt')
            ->leftJoin('rt.coverImage', 'c')
                ->addSelect('c');

        if (isset($criteria['slug'])) {
            $qb->andWhere('rt.slug = :slug')
            ->setParameter('slug', $criteria['slug']);
        }

        if (isset($criteria['id'])) {
            $qb->andWhere('rt.id = :id')
            ->setParameter('id', $criteria['id']);
        }

        return $qb->getQuery()->getOneOrNullResult();
    }

    public function getResumeTemplates(int $page): Pagerfanta
    {
        $qb = $this->createQueryBuilder('rt')
            ->leftJoin('rt.coverImage', 'c')
                ->addSelect('c')
            ->andWhere('rt.isActivated = true')
            ->orderBy('rt.updatedAt', 'DESC')
        ;

        return $this->createPaginator($qb->getQuery(), $page);
    }

    public function findAll()
    {
        return $this->createQueryBuilder('rt')
            ->leftJoin('rt.coverImage', 'c')
                ->addSelect('c')
            ->orderBy('rt.updatedAt', 'DESC')
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * @return ResumeTemplate[]
     */
    public function createPaginator(Query $query, int $page, $isAdmin = false): Pagerfanta
    {
        $paginator = new Pagerfanta(new DoctrineORMAdapter(($query)));
        if ($isAdmin) {
            $paginator->setMaxPerPage(ResumeTemplate::NB_ITEMS_ADMIN_LISTING);
        } else {
            $paginator->setMaxPerPage(ResumeTemplate::NB_ITEMS_LISTING);
        }
        $paginator->setCurrentPage($page);

        return $paginator;
    }
}

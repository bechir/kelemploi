<?php

/*
 * This file is part of the Kelemploi application.
 *
 * (c) Bechir Ba <bechiirr71@gmail.com>
 */

namespace App\Repository;

use App\Entity\Resume;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Pagerfanta\Pagerfanta;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Resume|null find($id, $lockMode = null, $lockVersion = null)
 * @method Resume|null findOneBy(array $criteria, array $orderBy = null)
 * @method Resume[]    findAll()
 * @method Resume[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ResumeRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Resume::class);
    }

    public function getResumes(int $page = 1): Pagerfanta
    {
        $qb = $this->createQueryBuilder('r')
            ->leftJoin('r.user', 'u')
            ->addSelect('u')
            ->leftJoin('r.studyLevel', 's')
                ->addSelect('s')
            ->leftJoin('r.experienceLevel', 'e')
                ->addSelect('e')
            ->leftJoin('r.gender', 'g')
                ->addSelect('g')
            ->leftJoin('r.workExperiences', 'w')
                ->addSelect('w')
            ->leftJoin('r.educations', 'edu')
                ->addSelect('edu')
        ;

        return $this->createPaginator($qb->getQuery(), $page);
    }

    public function findByFilters(array $filters, int $page): Pagerfanta
    {
        $qb = $this->createQueryBuilder('r')
                ->leftJoin('r.user', 'u')
                  ->addSelect('u')
                ->leftJoin('r.jobCategory', 'c')
                  ->addSelect('c')
                ->leftJoin('r.studyLevel', 's')
                    ->addSelect('s')
                ->leftJoin('r.experienceLevel', 'e')
                    ->addSelect('e')
                ->leftJoin('r.gender', 'g')
                    ->addSelect('g')
                ->leftJoin('r.workExperiences', 'w')
                    ->addSelect('w')
                ->leftJoin('r.educations', 'edu')
                    ->addSelect('edu')
                ->leftJoin('r.languageSkills', 'langP')
                    ->addSelect('langP')
                ->leftJoin('langP.name', 'lang')
                    ->addSelect('lang');

        if (isset($filters['levels'])) {
            $qb->andWhere($qb->expr()->in('s.level', ':levels'))
              ->setParameter(':levels', $filters['levels']);
        }

        if (isset($filters['experiences'])) {
            $qb->andWhere($qb->expr()->in('e.name', ':names'))
              ->setParameter(':names', $filters['experiences']);
        }

        if (isset($filters['genders'])) {
            $qb->andWhere($qb->expr()->in('g.name', ':genders'))
              ->setParameter(':genders', $filters['genders']);
        }

        if (isset($filters['job_category'])) {
            $qb->andWhere('c.slug = :jc_slug')
              ->setParameter(':jc_slug', $filters['job_category']);
        }

        if (isset($filters['langs'])) {
            $qb->andWhere($qb->expr()->in('lang.code', ':langs'))
              ->setParameter(':langs', $filters['langs']);
        }

        return $this->createPaginator($qb->getQuery(), $page);
    }

    public function adminGetRecents()
    {
        return $this->createQueryBuilder('r')
                ->orderBy('r.updatedAt', 'DESC')
                ->setMaxResults(5)
                ->getQuery()
                ->getResult();
    }

    public function adminGetResumes(int $page = 1): Pagerfanta
    {
        $qb = $this->createQueryBuilder('r')
            ->orderBy('r.updatedAt', 'DESC')
        ;

        return $this->createPaginator($qb->getQuery(), $page, true);
    }

    /**
     * @return Resume[]
     */
    public function createPaginator(Query $query, int $page, $isAdmin = false): Pagerfanta
    {
        $paginator = new Pagerfanta(new DoctrineORMAdapter(($query)));
        if ($isAdmin) {
            $paginator->setMaxPerPage(Resume::NB_ITEMS_ADMIN_LISTING);
        } else {
            $paginator->setMaxPerPage(Resume::NB_ITEMS_LISTING);
        }
        $paginator->setCurrentPage($page);

        return $paginator;
    }
}

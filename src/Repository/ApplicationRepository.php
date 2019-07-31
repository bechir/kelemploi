<?php

/*
 * This file is part of the Kelemploi application.
 *
 * (C) Bechir Ba <bechiirr71@gmail.com>
 */

namespace App\Repository;

use App\Entity\Application;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
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
            ->setMaxResults(Application::NUM_ITEMS_HOME)
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
            ->setMaxResults(Application::NUM_ITEMS_HOME)
        ->getResult();
    }
    // /**
    //  * @return Application[] Returns an array of Application objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Application
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}

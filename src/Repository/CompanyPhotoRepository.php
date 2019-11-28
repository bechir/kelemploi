<?php

/*
 * This file is part of the Kelemploi application.
 *
 * (c) Bechir Ba <bechiirr71@gmail.com>
 */

namespace App\Repository;

use App\Entity\CompanyPhoto;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method CompanyPhoto|null find($id, $lockMode = null, $lockVersion = null)
 * @method CompanyPhoto|null findOneBy(array $criteria, array $orderBy = null)
 * @method CompanyPhoto[]    findAll()
 * @method CompanyPhoto[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CompanyPhotoRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, CompanyPhoto::class);
    }

    // /**
    //  * @return CompanyPhoto[] Returns an array of CompanyPhoto objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?CompanyPhoto
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}

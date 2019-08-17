<?php

namespace App\Repository;

use App\Entity\CompanyBookmark;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method CompanyBookmark|null find($id, $lockMode = null, $lockVersion = null)
 * @method CompanyBookmark|null findOneBy(array $criteria, array $orderBy = null)
 * @method CompanyBookmark[]    findAll()
 * @method CompanyBookmark[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CompanyBookmarkRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, CompanyBookmark::class);
    }

    // /**
    //  * @return CompanyBookmark[] Returns an array of CompanyBookmark objects
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
    public function findOneBySomeField($value): ?CompanyBookmark
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

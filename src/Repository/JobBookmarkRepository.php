<?php

namespace App\Repository;

use App\Entity\JobBookmark;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method JobBookmark|null find($id, $lockMode = null, $lockVersion = null)
 * @method JobBookmark|null findOneBy(array $criteria, array $orderBy = null)
 * @method JobBookmark[]    findAll()
 * @method JobBookmark[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class JobBookmarkRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, JobBookmark::class);
    }

    // /**
    //  * @return JobBookmark[] Returns an array of JobBookmark objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('j')
            ->andWhere('j.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('j.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?JobBookmark
    {
        return $this->createQueryBuilder('j')
            ->andWhere('j.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}

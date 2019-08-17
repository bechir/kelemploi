<?php

namespace App\Repository;

use App\Entity\ResumeBookmark;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method ResumeBookmark|null find($id, $lockMode = null, $lockVersion = null)
 * @method ResumeBookmark|null findOneBy(array $criteria, array $orderBy = null)
 * @method ResumeBookmark[]    findAll()
 * @method ResumeBookmark[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ResumeBookmarkRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, ResumeBookmark::class);
    }

    // /**
    //  * @return ResumeBookmark[] Returns an array of ResumeBookmark objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('r.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?ResumeBookmark
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}

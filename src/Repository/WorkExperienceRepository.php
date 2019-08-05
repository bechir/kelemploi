<?php

namespace App\Repository;

use App\Entity\WorkExperience;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method WorkExperience|null find($id, $lockMode = null, $lockVersion = null)
 * @method WorkExperience|null findOneBy(array $criteria, array $orderBy = null)
 * @method WorkExperience[]    findAll()
 * @method WorkExperience[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class WorkExperienceRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, WorkExperience::class);
    }

    // /**
    //  * @return WorkExperience[] Returns an array of WorkExperience objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('w')
            ->andWhere('w.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('w.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?WorkExperience
    {
        return $this->createQueryBuilder('w')
            ->andWhere('w.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}

<?php

/*
 * This file is part of the Kelemploi application.
 *
 * (c) Bechir Ba <bechiirr71@gmail.com>
 */

namespace App\Repository;

use App\Entity\ResumeTemplateFile;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method ResumeTemplateFile|null find($id, $lockMode = null, $lockVersion = null)
 * @method ResumeTemplateFile|null findOneBy(array $criteria, array $orderBy = null)
 * @method ResumeTemplateFile[]    findAll()
 * @method ResumeTemplateFile[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ResumeTemplateFileRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, ResumeTemplateFile::class);
    }

    // /**
    //  * @return ResumeTemplateFile[] Returns an array of ResumeTemplateFile objects
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
    public function findOneBySomeField($value): ?ResumeTemplateFile
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

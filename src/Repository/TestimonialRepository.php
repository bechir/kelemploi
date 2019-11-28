<?php

/*
 * This file is part of the Kelemploi application.
 *
 * (c) Bechir Ba <bechiirr71@gmail.com>
 */

namespace App\Repository;

use App\Entity\Testimonial;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Testimonial|null find($id, $lockMode = null, $lockVersion = null)
 * @method Testimonial|null findOneBy(array $criteria, array $orderBy = null)
 * @method Testimonial[]    findAll()
 * @method Testimonial[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TestimonialRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Testimonial::class);
    }

    /**
     * @return Testimonial[] Returns an array of Testimonial objects
     */
    public function getEnabled()
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.isEnabled = true')
            ->orderBy('t.id', 'DESC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
}

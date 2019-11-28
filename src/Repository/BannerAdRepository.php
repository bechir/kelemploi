<?php

/*
 * This file is part of the Kelemploi application.
 *
 * (c) Bechir Ba <bechiirr71@gmail.com>
 */

namespace App\Repository;

use App\Entity\BannerAd;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method BannerAd|null find($id, $lockMode = null, $lockVersion = null)
 * @method BannerAd|null findOneBy(array $criteria, array $orderBy = null)
 * @method BannerAd[]    findAll()
 * @method BannerAd[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BannerAdRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, BannerAd::class);
    }
}

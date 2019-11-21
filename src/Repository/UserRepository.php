<?php

/*
 * This file is part of the Kelemploi application.
 *
 * (C) Bechir Ba <bechiirr71@gmail.com>
 */

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Pagerfanta\Pagerfanta;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Doctrine\ORM\EntityManagerInterface;

/**
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository
{
    private $em;
    public function __construct(RegistryInterface $registry, EntityManagerInterface $em)
    {
        parent::__construct($registry, User::class);

        $this->em = $em;
    }

    public function loadUserByUsername($username)
    {
        return $this->createQueryBuilder('u')
        ->where('u.phoneNumber = :phoneNumber OR u.email = :email')
        ->setParameter('phoneNumber', $username)
        ->setParameter('email', $username)
        ->getQuery()
        ->getOneOrNullResult();
    }

    public function getUsers(int $page): Pagerfanta
    {
        $qb = $this->createQueryBuilder('u')
              ->orderBy('u.submittedAt', 'DESC');

        return $this->createPaginator($qb->getQuery(), $page);
    }

    public function adminGetRecents()
    {
        return $this->createQueryBuilder('u')
                ->where('u.username is not null')
                ->orderBy('u.submittedAt', 'DESC')
                ->setMaxResults(5)
                ->getQuery()
                ->getResult();
    }

    public function getAdminUsers()
    {
        return $this->createQueryBuilder('u')
            ->where('u.isAdmin = true')
            ->orderBy('u.roles', 'ASC')
            ->getQuery()
            ->getResult();
    }

    public function findCandidates()
    {
        return $this->createQueryBuilder('u')
            ->leftJoin('u.accountType', 'a')
                ->addSelect('a')
            ->where('a.name = :name')
            ->setParameter('name', User::CANDIDATE)
            ->setMaxResults(User::NUM_ITEMS)
            ->getQuery()
        ->getResult();
    }

    /**
     * @return User[]
     */
    public function createPaginator(Query $query, int $page): Pagerfanta
    {
        $paginator = new Pagerfanta(new DoctrineORMAdapter(($query)));
        $paginator->setMaxPerPage(User::NUM_ITEMS);
        $paginator->setCurrentPage($page);

        return $paginator;
    }
}

<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Pagerfanta\Pagerfanta;
use Doctrine\ORM\Query;

/**
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, User::class);
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

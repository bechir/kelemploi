<?php

namespace App\Repository;

use App\Entity\Contest;
use Doctrine\ORM\Query;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Pagerfanta\Pagerfanta;

/**
 * @method Contest|null find($id, $lockMode = null, $lockVersion = null)
 * @method Contest|null findOneBy(array $criteria, array $orderBy = null)
 * @method Contest[]    findAll()
 * @method Contest[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ContestRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Contest::class);
    }

    public function findOneBy(array $criteria, array $orderBy = null): ?Contest
    {
        $qb = $this->createQueryBuilder('c')
            ->where('c.isArchived = false')
            ->andWhere('c.isActivated = true');

        if (isset($criteria['slug'])) {
            $qb->andWhere('c.slug = :slug')
            ->setParameter('slug', $criteria['slug']);
        }

        if (isset($criteria['id'])) {
            $qb->andWhere('c.id = :id')
            ->setParameter('id', $criteria['id']);
        }

        return $qb->getQuery()->getOneOrNullResult();
    }

    public function getContests(int $page = 1): Pagerfanta
    {
        $qb = $this->createQueryBuilder('c')
            ->where('c.isArchived = false')
            ->andWhere('c.isActivated = true')
            ->orderBy('c.createdAt', 'DESC')
        ;

        return $this->createPaginator($qb->getQuery(), $page);
    }

    public function adminGetContests(int $page = 1, $archived = false): Pagerfanta
    {
        $qb = $this->createQueryBuilder('c')
            ->orderBy('c.createdAt', 'DESC')
        ;

        if ($archived) {
            $qb->where('c.isArchived = true');
        } else {
            $qb->where('c.isArchived = false')
                ->orWhere('c.isArchived is null');
        }

        return $this->createPaginator($qb->getQuery(), $page, true);
    }

    public function findArchived(?string $slug): ?Contest
    {
        return $this->createQueryBuilder('c')
            ->where('c.isArchived = true')
            ->andWhere('c.slug = :slug')
            ->setParameter('slug', $slug)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function findAdminContestBySlug(string $slug): ?Contest
    {
        return $this->createQueryBuilder('c')
            ->where('c.slug = :slug')
            ->setParameter('slug', $slug)
            ->getQuery()
            ->getOneOrNullResult();
    }

    /**
     * @return Contest[]
     */
    public function createPaginator(Query $query, int $page, $isAdmin = false): Pagerfanta
    {
        $paginator = new Pagerfanta(new DoctrineORMAdapter(($query)));
        if($isAdmin) {
            $paginator->setMaxPerPage(Contest::NB_ITEMS_ADMIN_LISTING);
        }else {
            $paginator->setMaxPerPage(Contest::NB_ITEMS_LISTING);
        }
        $paginator->setCurrentPage($page);

        return $paginator;
    }
}

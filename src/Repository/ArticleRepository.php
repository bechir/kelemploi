<?php

namespace App\Repository;

use App\Entity\Article;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Pagerfanta\Pagerfanta;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Article|null find($id, $lockMode = null, $lockVersion = null)
 * @method Article|null findOneBy(array $criteria, array $orderBy = null)
 * @method Article[]    findAll()
 * @method Article[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ArticleRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Article::class);
    }

    public function findOneBy(array $criteria, array $orderBy = null): ?Article
    {
        $qb = $this->createQueryBuilder('a')
            ->leftJoin('a.coverImage', 'c')
                ->addSelect('c')
            ->where('a.isArchived = false')
            ->andWhere('a.isActivated = true');

        if (isset($criteria['slug'])) {
            $qb->andWhere('a.slug = :slug')
            ->setParameter('slug', $criteria['slug']);
        }

        if (isset($criteria['id'])) {
            $qb->andWhere('a.id = :id')
            ->setParameter('id', $criteria['id']);
        }

        return $qb->getQuery()->getOneOrNullResult();
    }

    public function findArchived(?string $slug): ?Article
    {
        return $this->createQueryBuilder('j')
            ->leftJoin('a.coverImage', 'c')
                ->addSelect('c')
            ->where('a.archived = true')
            ->andWhere('a.slug = :slug')
            ->setParameter('slug', $slug)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function getArticles(int $page): Pagerfanta
    {
        $qb = $this->createQueryBuilder('a')
            ->leftJoin('a.coverImage', 'c')
                ->addSelect('c')
            ->where('a.isArchived = false')
            ->andWhere('a.isActivated = true')
            ->orderBy('a.updatedAt', 'DESC')
        ;

        return $this->createPaginator($qb->getQuery(), $page);
    }

    public function findAll()
    {
        return $this->createQueryBuilder('a')
            ->leftJoin('a.coverImage', 'c')
                ->addSelect('c')
            ->where('a.isArchived = false')
            ->orderBy('a.updatedAt', 'DESC')
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * @return Article[]
     */
    public function createPaginator(Query $query, int $page, $isAdmin = false): Pagerfanta
    {
        $paginator = new Pagerfanta(new DoctrineORMAdapter(($query)));
        if($isAdmin) {
            $paginator->setMaxPerPage(Article::NB_ITEMS_ADMIN_LISTING);
        }else {
            $paginator->setMaxPerPage(Article::NB_ITEMS_LISTING);
        }
        $paginator->setCurrentPage($page);

        return $paginator;
    }
}

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

    public function getArticles(int $page): Pagerfanta
    {
        $qb = $this->createQueryBuilder('a')
            ->leftJoin('a.coverImage', 'c')
                ->addSelect('c')
            ->orderBy('a.updatedAt', 'DESC')
        ;

        return $this->createPaginator($qb->getQuery(), $page);
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

<?php

namespace App\Repository;

use App\Entity\Post;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;

/**
 * @method null|Post find($id, $lockMode = null, $lockVersion = null)
 * @method null|Post findOneBy(array $criteria, array $orderBy = null)
 * @method Post[]    findAll()
 * @method Post[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PostRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry, PaginatorInterface $paginator)
    {
        parent::__construct($registry, Post::class);
        $this->paginator = $paginator;
    }

    /**
     * findLastest.
     */
    public function findLastest()
    {
        return $this->findPublished()
            ->setMaxResults(6)
            ->getQuery()
            ->getResult()
        ;
    }

    public function findPublished(): QueryBuilder
    {
        return $this->createQueryBuilder('p')
            ->where('p.published = true')
        ;
    }

    public function findByCategory(int $idCategory, int $page): PaginationInterface
    {
        $query = $this->findPublished()
            ->andWhere('p.categories = :idCategory')
            ->setParameter('idCategory', $idCategory)
        ;

        return $this->paginator->paginate(
            $query->getQuery(),
            $page,
            12
        );
    }

    public function findByTag(int $idTag, int $page): PaginationInterface
    {
        $query = $this->findPublished()
            ->innerJoin('p.tags', 't')
            ->andWhere('t.id = :idTag')
            ->setParameter(':idTag', $idTag)
        ;

        return $this->paginator->paginate(
            $query->getQuery(),
            $page,
            5
        );
    }
}

<?php

namespace App\Repository;

use App\Entity\Article;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Article|null find($id, $lockMode = null, $lockVersion = null)
 * @method Article|null findOneBy(array $criteria, array $orderBy = null)
 * @method Article[]    findAll()
 * @method Article[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ArticleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Article::class);
    }

    /**
     * Return all the articles published with their comments
     *
     * @return mixed
     */
    public function findAllWithComments()
    {
        return $this->createQueryBuilder('a')
            ->where('a.published = TRUE')
            ->orderBy('a.created_at', 'desc')
            ->leftJoin('a.comments', 'c')
            ->addSelect('c')
            ->addOrderBy('c.created_at', 'desc')
            ->getQuery()
            ->getResult();
    }

    public function findAllWithPaging($currentPage, $nbPerPage)
    {
        $query = $this->createQueryBuilder('a')
            ->where('a.published = TRUE')
            ->orderBy('a.created_at', 'desc')
            ->getQuery()
            ->setFirstResult(($currentPage- 1) * $nbPerPage)
            ->setMaxResults($nbPerPage);

        return new Paginator($query);
    }

    // /**
    //  * @return Article[] Returns an array of Article objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Article
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}

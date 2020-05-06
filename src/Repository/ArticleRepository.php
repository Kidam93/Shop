<?php

namespace App\Repository;

use App\Entity\Article;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
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

    public function findMaxResult($max){
        return $this->createQueryBuilder('a')
            ->setMaxResults($max)
            ->getQuery()
            ->getResult()
        ;
    }

    public function findSearch(string $search){
        $rawSql = "SELECT *
                    FROM article 
                    WHERE title LIKE '%$search%'
                    OR description LIKE '%$search%'
                    OR price = '$search'";
        $stmt = $this->getEntityManager()->getConnection()->prepare($rawSql);
        $stmt->execute([$search]);
        return $stmt->fetchAll();
    }

    public function findNbArticles($nbPages, $nbArticles){
        $calc = ($nbArticles - 1) * $nbPages;
        $rawSql = "SELECT *
                    FROM article
                    LIMIT $nbPages OFFSET $calc";
        $stmt = $this->getEntityManager()->getConnection()->prepare($rawSql);
        $stmt->execute([$nbPages, $nbArticles]);
        return $stmt->fetchAll();
    }
}

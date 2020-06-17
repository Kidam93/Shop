<?php

namespace App\Repository;

use App\Entity\Basket;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Basket|null find($id, $lockMode = null, $lockVersion = null)
 * @method Basket|null findOneBy(array $criteria, array $orderBy = null)
 * @method Basket[]    findAll()
 * @method Basket[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BasketRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Basket::class);
    }

    // /**
    //  * @return Basket[] Returns an array of Basket objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('b.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Basket
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

    public function findJoin($user_id, $article_id){
        $rawSql = "SELECT * FROM basket 
                    JOIN basket_user 
                    ON basket.id = basket_user.basket_id
                    JOIN basket_article
                    ON basket.id = basket_article.basket_id
                    WHERE basket_user.user_id = $user_id
                    AND basket_article.article_id = $article_id";
        $stmt = $this->getEntityManager()->getConnection()->prepare($rawSql);
        $stmt->execute([$user_id, $article_id]);
        return $stmt->fetchAll();
    }

    public function updateQuantity($basket_id){
        $rawSql = "UPDATE basket 
                    SET quantity = quantity + 1
                    WHERE basket.id = $basket_id";
        $stmt = $this->getEntityManager()->getConnection()->prepare($rawSql);
        return $stmt->execute([$basket_id]);
        // return $stmt->fetchAll();
    }

    public function findBasket($userId){
        $rawSql = "SELECT *, basket.quantity FROM basket
                    JOIN basket_user
                    ON basket.id = basket_user.basket_id
                    JOIN basket_article
                    ON basket.id = basket_article.basket_id
                    JOIN article
                    ON article.id = basket_article.article_id
                    WHERE basket_user.user_id = $userId";

        $stmt = $this->getEntityManager()->getConnection()->prepare($rawSql);
        $stmt->execute([$userId]);
        return $stmt->fetchAll();
    }

    public function selectJoin($id, $user){
        $rawSql = "SELECT basket.id FROM basket
                    JOIN basket_user
                    ON basket_user.basket_id = basket.id
                    JOIN basket_article
                    ON basket_article.basket_id = basket.id
                    WHERE basket_article.article_id = $id
                    AND basket_user.user_id = $user";
        $stmt = $this->getEntityManager()->getConnection()->prepare($rawSql);
        $stmt->execute([$id, $user]);
        return $stmt->fetchAll();
    }

    public function deleteBasket($basketId){
        $rawSql = "DELETE FROM basket
                    WHERE basket.id = $basketId";
        $stmt = $this->getEntityManager()->getConnection()->prepare($rawSql);
        return $stmt->execute([$basketId]);
    }

    public function totalQTE($user){
        $rawSql = "SELECT * FROM basket
                    JOIN basket_user
                    ON basket_user.basket_id = basket.id
                    WHERE basket_user.user_id = $user";
        $stmt = $this->getEntityManager()->getConnection()->prepare($rawSql);
        $stmt->execute([$user]);
        return $stmt->fetchAll();
    }
}

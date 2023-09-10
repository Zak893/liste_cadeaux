<?php

namespace App\Repository;

use App\Entity\Gift;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Gift>
 *
 * @method Gift|null find($id, $lockMode = null, $lockVersion = null)
 * @method Gift|null findOneBy(array $criteria, array $orderBy = null)
 * @method Gift[]    findAll()
 * @method Gift[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class GiftRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Gift::class);
    }

    /**
    * @return Gift[] Returns an array of Gift objects
     */
    public function findByUserIdField($value): array
    {
       return $this->createQueryBuilder('g')
           ->andWhere('g.user_id = :val')
           ->setParameter('val', $value)
          ->orderBy('g.user_id', 'ASC')
           ->setMaxResults(10)
          ->getQuery()
           ->getResult()
        ;
    }
    /**
     * @return Gift[] Returns an array of Gift objects
     */
    public function findByListIdField($value): array
    {
        return $this->createQueryBuilder('g')
            ->andWhere('g.list_id = :val')
            ->setParameter('val', $value)
            ->orderBy('g.list_id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
            ;
    }
//    public function findOneBySomeField($value): ?Gift
//    {
//        return $this->createQueryBuilder('g')
//            ->andWhere('g.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}

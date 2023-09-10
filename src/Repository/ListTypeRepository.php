<?php

namespace App\Repository;

use App\Entity\ListType;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ListType>
 *
 * @method ListType|null find($id, $lockMode = null, $lockVersion = null)
 * @method ListType|null findOneBy(array $criteria, array $orderBy = null)
 * @method ListType[]    findAll()
 * @method ListType[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ListTypeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ListType::class);
    }

    /**
     * @return ListType[] Returns an array of ListType objects
     */
    public function findByExampleField($value): array
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.user_id = :val')
            ->setParameter('val', $value)
            ->orderBy('l.user_id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    /**
     * @return ListType[] Returns an array of ListType objects
     */
    public function findByIdField($value): array
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.id = :val')
            ->setParameter('val', $value)
            ->orderBy('l.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
            ;
    }

    public function findOneBySomeField($value): ?ListType
   {
       return $this->createQueryBuilder('l')
           ->andWhere('l.id = :val')
           ->setParameter('val', )
          ->getQuery()
           ->getOneOrNullResult()
      ;
   }
}

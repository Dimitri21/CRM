<?php

namespace App\Repository;

use App\Entity\CategoryContact;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method CategoryContact|null find($id, $lockMode = null, $lockVersion = null)
 * @method CategoryContact|null findOneBy(array $criteria, array $orderBy = null)
 * @method CategoryContact[]    findAll()
 * @method CategoryContact[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CategoryContactRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CategoryContact::class);
    }

    // /**
    //  * @return CategoryContact[] Returns an array of CategoryContact objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?CategoryContact
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}

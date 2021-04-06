<?php

namespace App\Repository;

use App\Entity\Contact;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Contact|null find($id, $lockMode = null, $lockVersion = null)
 * @method Contact|null findOneBy(array $criteria, array $orderBy = null)
 * @method Contact[]    findAll()
 * @method Contact[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ContactRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Contact::class);
    }

     /**
     * @return Contact[] Returns an array of Contact objects
     */
    public function findLatestContact(): array
    {

            return $this->createQueryBuilder('q')
                ->setMaxResults(8)
                ->orderBy('q.createdAt', 'DESC')
                ->getQuery()
                ->getResult()
                ;

    }

    /**
     * @return Contact[] Returns an array of Contact objects
     */
    public function findContact($value): array {
        if ($value) {
            $value = '%'.$value.'%';
            return $this->createQueryBuilder('c')
                ->orWhere('c.firstName like :val')
                ->orWhere('c.lastName like :val')
                ->orWhere('c.phone like :val')
                ->orWhere('c.email like :val')
                ->setParameter('val', $value)
                ->getQuery()
                ->getResult()
                ;
        } else {
            return ['error'];
        }
    }


    // /**
    //  * @return Contact[] Returns an array of Contact objects
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
    public function findOneBySomeField($value): ?Contact
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

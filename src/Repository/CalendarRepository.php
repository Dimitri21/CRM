<?php

namespace App\Repository;

use App\Entity\Calendar;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Security;


/**
 * @method Calendar|null find($id, $lockMode = null, $lockVersion = null)
 * @method Calendar|null findOneBy(array $criteria, array $orderBy = null)
 * @method Calendar[]    findAll()
 * @method Calendar[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CalendarRepository extends ServiceEntityRepository
{
    public function __construct(
        ManagerRegistry $registry,
        Security $security)
    {
        parent::__construct($registry, Calendar::class);
        $this->security = $security;
    }

    /**
    * @return Calendar[] Returns an array of Calendar objects
    */

    public function getUserCalendar()
    {

        $user = $this->security->getUser();

        return $this->createQueryBuilder('c')
            ->andWhere('c.createdBy = :user')
            ->orWhere(':user MEMBER OF c.members')
            ->setParameter('user', $user)
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * @return Calendar[] Returns an array of Calendar objects
     */
    public function findCalendar($value): array {

        $user = $this->security->getUser();

        $value = '%'.$value.'%';
        if ($value) {
            return $this->createQueryBuilder('c')
                ->andWhere('c.createdBy = :user')
                ->orWhere(':user MEMBER OF c.members')
                ->andWhere('c.title like :val')
                ->setParameter('val', $value)
                ->setParameter('user', $user)
                ->getQuery()
                ->getResult()
                ;
        } else {
            return ['error'];
        }
    }

    /*
    public function findOneBySomeField($value): ?Calendar
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

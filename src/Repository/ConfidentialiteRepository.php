<?php

namespace App\Repository;

use App\Entity\Confidentialite;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Confidentialite|null find($id, $lockMode = null, $lockVersion = null)
 * @method Confidentialite|null findOneBy(array $criteria, array $orderBy = null)
 * @method Confidentialite[]    findAll()
 * @method Confidentialite[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ConfidentialiteRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Confidentialite::class);
    }

    // /**
    //  * @return Confidentialite[] Returns an array of Confidentialite objects
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
    public function findOneBySomeField($value): ?Confidentialite
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

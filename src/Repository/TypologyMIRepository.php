<?php

namespace App\Repository;

use App\Entity\TypologyMI;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method TypologyMI|null find($id, $lockMode = null, $lockVersion = null)
 * @method TypologyMI|null findOneBy(array $criteria, array $orderBy = null)
 * @method TypologyMI[]    findAll()
 * @method TypologyMI[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TypologyMIRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TypologyMI::class);
    }

    // /**
    //  * @return TypologyMI[] Returns an array of TypologyMI objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('t.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?TypologyMI
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}

<?php

namespace App\Repository;

use App\Entity\ColorSI;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ColorSI|null find($id, $lockMode = null, $lockVersion = null)
 * @method ColorSI|null findOneBy(array $criteria, array $orderBy = null)
 * @method ColorSI[]    findAll()
 * @method ColorSI[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ColorSIRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ColorSI::class);
    }

    // /**
    //  * @return ColorSI[] Returns an array of ColorSI objects
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
    public function findOneBySomeField($value): ?ColorSI
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

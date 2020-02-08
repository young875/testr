<?php

namespace App\Repository;

use App\Entity\Carburations;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Carburations|null find($id, $lockMode = null, $lockVersion = null)
 * @method Carburations|null findOneBy(array $criteria, array $orderBy = null)
 * @method Carburations[]    findAll()
 * @method Carburations[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CarburationsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Carburations::class);
    }

    // /**
    //  * @return Carburations[] Returns an array of Carburations objects
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
    public function findOneBySomeField($value): ?Carburations
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

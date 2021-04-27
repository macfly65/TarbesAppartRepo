<?php

namespace App\Repository;

use App\Entity\Edl;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Edl|null find($id, $lockMode = null, $lockVersion = null)
 * @method Edl|null findOneBy(array $criteria, array $orderBy = null)
 * @method Edl[]    findAll()
 * @method Edl[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EdlRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Edl::class);
    }

    // /**
    //  * @return Edl[] Returns an array of Edl objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('e.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Edl
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}

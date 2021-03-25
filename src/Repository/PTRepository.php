<?php

namespace App\Repository;

use App\Entity\PT;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method PT|null find($id, $lockMode = null, $lockVersion = null)
 * @method PT|null findOneBy(array $criteria, array $orderBy = null)
 * @method PT[]    findAll()
 * @method PT[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PTRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PT::class);
    }

    // /**
    //  * @return PT[] Returns an array of PT objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?PT
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}

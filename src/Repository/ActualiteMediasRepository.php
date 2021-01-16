<?php

namespace App\Repository;

use App\Entity\ActualiteMedias;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method ActualiteMedias|null find($id, $lockMode = null, $lockVersion = null)
 * @method ActualiteMedias|null findOneBy(array $criteria, array $orderBy = null)
 * @method ActualiteMedias[]    findAll()
 * @method ActualiteMedias[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ActualiteMediasRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ActualiteMedias::class);
    }

    // /**
    //  * @return ActualiteMedias[] Returns an array of ActualiteMedias objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?ActualiteMedias
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}

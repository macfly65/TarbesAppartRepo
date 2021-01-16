<?php

namespace App\Repository;

use App\Entity\CategsDocuments;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method CategsDocuments|null find($id, $lockMode = null, $lockVersion = null)
 * @method CategsDocuments|null findOneBy(array $criteria, array $orderBy = null)
 * @method CategsDocuments[]    findAll()
 * @method CategsDocuments[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CategsDocumentsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CategsDocuments::class);
    }

    // /**
    //  * @return CategsDocuments[] Returns an array of CategsDocuments objects
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
    public function findOneBySomeField($value): ?CategsDocuments
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

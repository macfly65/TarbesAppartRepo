<?php

namespace App\Repository;

use App\Entity\ActualiteCategs;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method ActualiteCategs|null find($id, $lockMode = null, $lockVersion = null)
 * @method ActualiteCategs|null findOneBy(array $criteria, array $orderBy = null)
 * @method ActualiteCategs[]    findAll()
 * @method ActualiteCategs[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ActualiteCategsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ActualiteCategs::class);
    }

    // /**
    //  * @return ActualiteCategs[] Returns an array of ActualiteCategs objects
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
    public function findOneBySomeField($value): ?ActualiteCategs
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

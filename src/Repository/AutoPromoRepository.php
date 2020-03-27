<?php

namespace App\Repository;

use App\Entity\AutoPromo;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method AutoPromo|null find($id, $lockMode = null, $lockVersion = null)
 * @method AutoPromo|null findOneBy(array $criteria, array $orderBy = null)
 * @method AutoPromo[]    findAll()
 * @method AutoPromo[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AutoPromoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AutoPromo::class);
    }

    // /**
    //  * @return AutoPromo[] Returns an array of AutoPromo objects
    //  */
//    
//    public function findByExampleField($value)
//    {
//        return $this->s('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('a.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }
    

    /*
    public function findOneBySomeField($value): ?AutoPromo
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

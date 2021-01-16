<?php

namespace App\Repository;

use App\Entity\AppartementMedias;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method AppartementMedias|null find($id, $lockMode = null, $lockVersion = null)
 * @method AppartementMedias|null findOneBy(array $criteria, array $orderBy = null)
 * @method AppartementMedias[]    findAll()
 * @method AppartementMedias[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AppartementMediasRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AppartementMedias::class);
    }

    // /**
    //  * @return AppartementMedias[] Returns an array of AppartementMedias objects
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
    public function findOneBySomeField($value): ?AppartementMedias
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

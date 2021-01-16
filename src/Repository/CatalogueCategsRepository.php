<?php

namespace App\Repository;

use App\Entity\CatalogueCategs;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method CatalogueCategs|null find($id, $lockMode = null, $lockVersion = null)
 * @method CatalogueCategs|null findOneBy(array $criteria, array $orderBy = null)
 * @method CatalogueCategs[]    findAll()
 * @method CatalogueCategs[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CatalogueCategsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CatalogueCategs::class);
    }

    // /**
    //  * @return CatalogueCategs[] Returns an array of CatalogueCategs objects
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
    public function findOneBySomeField($value): ?CatalogueCategs
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

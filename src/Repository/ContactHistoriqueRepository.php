<?php

namespace App\Repository;

use App\Entity\ContactHistorique;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ContactHistorique|null find($id, $lockMode = null, $lockVersion = null)
 * @method ContactHistorique|null findOneBy(array $criteria, array $orderBy = null)
 * @method ContactHistorique[]    findAll()
 * @method ContactHistorique[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ContactHistoriqueRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ContactHistorique::class);
    }

    // /**
    //  * @return ContactHistorique[] Returns an array of ContactHistorique objects
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
    public function findOneBySomeField($value): ?ContactHistorique
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

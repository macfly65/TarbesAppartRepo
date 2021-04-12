<?php

namespace App\Repository;

use App\Entity\DocumentLocataire;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method DocumentLocataire|null find($id, $lockMode = null, $lockVersion = null)
 * @method DocumentLocataire|null findOneBy(array $criteria, array $orderBy = null)
 * @method DocumentLocataire[]    findAll()
 * @method DocumentLocataire[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DocumentLocataireRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DocumentLocataire::class);
    }

    // /**
    //  * @return DocumentLocataire[] Returns an array of DocumentLocataire objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('d.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?DocumentLocataire
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}

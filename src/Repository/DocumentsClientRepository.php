<?php

namespace App\Repository;

use App\Entity\DocumentsClient;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method DocumentsClient|null find($id, $lockMode = null, $lockVersion = null)
 * @method DocumentsClient|null findOneBy(array $criteria, array $orderBy = null)
 * @method DocumentsClient[]    findAll()
 * @method DocumentsClient[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DocumentsClientRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DocumentsClient::class);
    }

    // /**
    //  * @return DocumentsClient[] Returns an array of DocumentsClient objects
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
    public function findOneBySomeField($value): ?DocumentsClient
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

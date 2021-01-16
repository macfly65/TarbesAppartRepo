<?php

namespace App\Repository;

use App\Entity\CommentaireActualite;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method CommentaireActualite|null find($id, $lockMode = null, $lockVersion = null)
 * @method CommentaireActualite|null findOneBy(array $criteria, array $orderBy = null)
 * @method CommentaireActualite[]    findAll()
 * @method CommentaireActualite[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CommentaireActualiteRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CommentaireActualite::class);
    }

    // /**
    //  * @return CommentaireActualite[] Returns an array of CommentaireActualite objects
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
    public function findOneBySomeField($value): ?CommentaireActualite
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

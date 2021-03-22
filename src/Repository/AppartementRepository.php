<?php

namespace App\Repository;

use App\Entity\Appartement;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Appartement|null find($id, $lockMode = null, $lockVersion = null)
 * @method Appartement|null findOneBy(array $criteria, array $orderBy = null)
 * @method Appartement[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AppartementRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Appartement::class);
    }

    public function findAppartAdmin($numero, int $residence)
    {
        $qb = $this->createQueryBuilder('b');
        if(!$numero == ""){
            $qb->where('b.numero = :numero')->setParameter('numero', $numero);
        }
        if(!$residence == 0){
            $qb->andWhere('b.residence = :residence')->setParameter('residence', $residence);
        }
        $query = $qb->getQuery();
        return $query->execute();
    }

    public function findAppartAdminDispo($numero, int $residence, $dateDay)
    {
        $qb = $this->createQueryBuilder('b');

        $qb->andWhere('b.disponibilite != :dateDay')->setParameter('dateDay', $dateDay);

        $query = $qb->getQuery();
        return $query->execute();
    }

       public function findAppartfront()
    {
        return $this->createQueryBuilder('b')
            ->setMaxResults(8)
            ->getQuery()
            ->getResult()
        ;
    }
       public function findAppartfrontby3()
    {
        return $this->createQueryBuilder('b')
            ->setMaxResults(3)
            ->getQuery()
            ->getResult()
        ;
    }

    public function findAll()
    {
        return $this->findBy(array(), array('disponibilite' => 'ASC'));
    }

    // /**
    //  * @return Appartement[] Returns an array of Appartement objects
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
    public function findOneBySomeField($value): ?Appartement
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

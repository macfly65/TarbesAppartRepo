<?php

namespace App\Repository;

use App\Entity\prestataire;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method prestataire|null find($id, $lockMode = null, $lockVersion = null)
 * @method prestataire|null findOneBy(array $criteria, array $orderBy = null)
 * @method prestataire[]    findAll()
 * @method prestataire[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PrestataireRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, prestataire::class);
    }
}



<?php

namespace App\Repository;

use App\Entity\Availability;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Availability>
 */
class AvailabilityRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Availability::class);
    }

    public function findAvailableVehicles(\DateTime $departDate, \DateTime $returnDate)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.status = :status')
            ->andWhere('a.depart_date <= :departDate AND a.return_date >= :returnDate')
            ->setParameter('status', 'available')
            ->setParameter('departDate', $departDate)
            ->setParameter('returnDate', $returnDate)
            ->getQuery()
            ->getResult();
    }
}

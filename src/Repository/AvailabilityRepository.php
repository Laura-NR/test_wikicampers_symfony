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

    public function findAvailableVehicles(\DateTime $departDate, \DateTime $returnDate, ?float $maxPrice = null)
    {
        $days = $returnDate->diff($departDate)->days + 1;

        $queryBuilder = $this->createQueryBuilder('a')
            ->andWhere('a.status = :status')
            ->andWhere('a.depart_date <= :returnDate AND a.return_date >= :departDate')
            ->setParameter('status', true) // Available
            ->setParameter('departDate', $departDate)
            ->setParameter('returnDate', $returnDate);

        if ($maxPrice !== null) {
            $queryBuilder->andWhere('a.price_per_day * :days <= :maxPrice')
                ->setParameter('days', $days)
                ->setParameter('maxPrice', $maxPrice);
        }

        return $queryBuilder->getQuery()->getResult();
    }
}

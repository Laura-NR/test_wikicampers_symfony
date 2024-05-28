<?php

namespace App\Repository;

use App\Entity\Availability;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Psr\Log\LoggerInterface;

/**
 * @extends ServiceEntityRepository<Availability>
 */
class AvailabilityRepository extends ServiceEntityRepository
{
    private LoggerInterface $logger;

    public function __construct(ManagerRegistry $registry, LoggerInterface $logger)
    {
        parent::__construct($registry, Availability::class);
        $this->logger = $logger;
    }

    public function findAvailableVehicles(\DateTime $departDate, \DateTime $returnDate, ?float $maxPrice = null)
    {
        $days = $returnDate->diff($departDate)->days + 1;

        $queryBuilder = $this->createQueryBuilder('a')
            ->andWhere('a.status = :status')
            ->andWhere('a.depart_date <= :departDate AND a.return_date >= :returnDate')
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

    public function filterSuggestedAvailabilitiesByPrice(\DateTime $departDate, \DateTime $returnDate, ?float $maxPrice = null, int $days = 1, array $suggestedAvailabilities = [])
    {
        $returnDateBefore = (clone $returnDate)->modify("-{$days} day");
        $departDateAfter = (clone $departDate)->modify("+{$days} day");

        $this->logger->info('Return Date Before:', ['returnDateBefore' => $returnDateBefore]);
        $this->logger->info('Depart Date After:', ['departDateAfter' => $departDateAfter]);

        // Filter suggested availabilities by price
        $filteredAvailabilities  = array_filter($suggestedAvailabilities, function (Availability $availability) use ($maxPrice, $returnDate, $departDate) {
            $biggestDepartDate = $availability->getDepartDate() > $departDate ? $availability->getDepartDate() : $departDate;
            $smallestReturnDate = $availability->getReturnDate() < $returnDate ? $availability->getReturnDate() : $returnDate;
            $days = $smallestReturnDate->diff($biggestDepartDate)->days + 1;
            return $availability->getPricePerDay() * $days <= $maxPrice;
        });

        return $filteredAvailabilities ;
    }

    public function findSuggestedAvailableVehicles(\DateTime $departDate, \DateTime $returnDate, ?float $maxPrice = null, int $days = 1)
    {
        $returnDateBefore = (clone $returnDate)->modify("-{$days} day");
        $departDateAfter = (clone $departDate)->modify("+{$days} day");

        $this->logger->info('Return Date Before:', ['returnDateBefore' => $returnDateBefore]);
        $this->logger->info('Depart Date After:', ['departDateAfter' => $departDateAfter]);

        $queryBuilder = $this->createQueryBuilder('a')
            ->andWhere('a.status = :status')
            ->andWhere(
                '(a.depart_date <= :departDateAfter AND a.return_date >= :returnDateBefore)'
            )
            ->setParameter('status', true)
            ->setParameter('returnDateBefore', $returnDateBefore)
            ->setParameter('departDateAfter', $departDateAfter);

        $this->logger->info('Executing findSuggestedAvailableVehicles query', [
            'query' => $queryBuilder->getQuery()->getSQL(),
            'parameters' => $queryBuilder->getParameters()
        ]);

        $suggestedAvailabilities = $queryBuilder->getQuery()->getResult();

        if ($maxPrice !== null) {
            return $this->filterSuggestedAvailabilitiesByPrice($departDate, $returnDate, $maxPrice, $days, $suggestedAvailabilities);
        }

        return $suggestedAvailabilities;

        
    }

}

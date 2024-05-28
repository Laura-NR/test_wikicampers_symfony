<?php

namespace App\Tests\Repository;

use App\Entity\Availability;
use App\Entity\Vehicle;
use App\Repository\AvailabilityRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\SchemaTool;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Psr\Log\LoggerInterface;
use Doctrine\Persistence\ManagerRegistry;
use DateTime;

class AvailabilityRepositoryTest extends KernelTestCase
{
    private ?EntityManager $entityManager;
    private AvailabilityRepository $availabilityRepository;
    private LoggerInterface $logger;
    private ManagerRegistry $managerRegistry;

    protected function setUp(): void
    {
        self::bootKernel();

        $this->entityManager = self::$kernel->getContainer()
            ->get('doctrine')
            ->getManager();

        $this->managerRegistry = self::$kernel->getContainer()
            ->get('doctrine');

        $this->logger = $this->createMock(LoggerInterface::class);

        $this->availabilityRepository = new AvailabilityRepository(
            $this->managerRegistry,
            $this->logger
        );

        // Create schema
        $schemaTool = new SchemaTool($this->entityManager);
        $metadata = $this->entityManager->getClassMetadata(Availability::class);
        $schemaTool->dropSchema([$metadata]);
        $schemaTool->createSchema([$metadata]);
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        $this->entityManager->close();
        $this->entityManager = null; 
    }

    public function testFindAvailableVehicles(): void
    {
        // Set up test data
        $vehicle = new Vehicle();
        $vehicle->setMake('Test Make');
        $vehicle->setModel('Test Model');

        $availability = new Availability();
        $availability->setDepartDate(new DateTime('2024-05-28'));
        $availability->setReturnDate(new DateTime('2024-06-03'));
        $availability->setPricePerDay(50);
        $availability->setStatus(true);
        $availability->setVehicle($vehicle);

        $this->entityManager->persist($vehicle);
        $this->entityManager->persist($availability);
        $this->entityManager->flush();

        $results = $this->availabilityRepository->findAvailableVehicles(
            new DateTime('2024-05-28'),
            new DateTime('2024-06-03'),
            400
        );

        $this->assertCount(1, $results);
        $this->assertSame($availability, $results[0]);
    }

    public function testFindSuggestedAvailableVehicles(): void
    {
        // Set up test data
        $vehicle = new Vehicle();
        $vehicle->setMake('Test Make');
        $vehicle->setModel('Test Model');

        $availability = new Availability();
        $availability->setDepartDate(new DateTime('2024-05-27'));
        $availability->setReturnDate(new DateTime('2024-06-04'));
        $availability->setPricePerDay(50);
        $availability->setStatus(true);
        $availability->setVehicle($vehicle);

        $this->entityManager->persist($vehicle);
        $this->entityManager->persist($availability);
        $this->entityManager->flush();

        $results = $this->availabilityRepository->findSuggestedAvailableVehicles(
            new DateTime('2024-05-28'),
            new DateTime('2024-06-03'),
            400,
            1
        );

        $this->assertCount(1, $results);
        $this->assertSame($availability, $results[0]);
    }

    public function testFilterSuggestedAvailabilitiesByPrice(): void
    {
        // Set up test data
        $vehicle = new Vehicle();
        $vehicle->setMake('Test Make');
        $vehicle->setModel('Test Model');
        $this->entityManager->persist($vehicle);

        $availability = new Availability();
        $availability->setDepartDate(new DateTime('2024-05-27'));
        $availability->setReturnDate(new DateTime('2024-06-04'));
        $availability->setPricePerDay(50);
        $availability->setStatus(true);
        $availability->setVehicle($vehicle);

        $availabilities = [$availability];

        $this->entityManager->persist($availability);
        $this->entityManager->flush();

        $results = $this->availabilityRepository->filterSuggestedAvailabilitiesByPrice(
            new DateTime('2024-05-28'),
            new DateTime('2024-06-03'),
            400,
            1,
            $availabilities
        );

        $this->assertCount(1, $results);
        $this->assertSame($availability, $results[0]);
    }
}

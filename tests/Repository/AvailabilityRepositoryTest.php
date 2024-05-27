<?php 

namespace App\Tests\Repository;

use App\Entity\Availability;
use App\Entity\Vehicle;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class AvailabilityRepositoryTest extends KernelTestCase
{
    private $entityManager;

    protected function setUp(): void
    {
        $kernel = self::bootKernel();
        $this->entityManager = $kernel->getContainer()->get('doctrine')->getManager();
    }

    public function testFindAvailableVehicles()
    {
        $departDate = new \DateTime('2024-05-01');
        $returnDate = new \DateTime('2024-05-10');

        $repository = $this->entityManager->getRepository(Availability::class);
        $availableVehicles = $repository->findAvailableVehicles($departDate, $returnDate);

        $this->assertIsArray($availableVehicles);
        // Additional assertions to check if the vehicles are actually available
        foreach ($availableVehicles as $availability) {
            $this->assertTrue($availability->getDepartDate() <= $departDate);
            $this->assertTrue($availability->getReturnDate() >= $returnDate);
            $this->assertEquals('available', $availability->getStatus());
        }
    }

    protected function tearDown(): void
    {
        parent::tearDown();

        // doing this is recommended to avoid memory leaks
        $this->entityManager->close();
        $this->entityManager = null;
    }
}
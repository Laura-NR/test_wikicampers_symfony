<?php

namespace App\Test\Controller;

use App\Entity\Availability;
use App\Entity\Vehicle;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class AvailabilityControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private EntityManagerInterface $manager;
    private EntityRepository $availabilityRepository;
    private EntityRepository $vehicleRepository;
    private string $path = '/availability/';

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->manager = static::getContainer()->get('doctrine')->getManager();
        $this->availabilityRepository = $this->manager->getRepository(Availability::class);
        $this->vehicleRepository = $this->manager->getRepository(Vehicle::class);

        foreach ($this->availabilityRepository->findAll() as $object) {
            $this->manager->remove($object);
        }

        foreach ($this->vehicleRepository->findAll() as $object) {
            $this->manager->remove($object);
        }

        $this->manager->flush();
    }

    public function testIndex(): void
    {
        $crawler = $this->client->request('GET', $this->path);

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Availability index');

        // Use the $crawler to perform additional assertions e.g.
        // self::assertSame('Some text on the page', $crawler->filter('.p')->first());
    }

    public function testNew(): void
    {
        // Create a new Vehicle entity
        $vehicle = new Vehicle();
        $vehicle->setMake('Test Make');
        $vehicle->setModel('Test Model');
        $this->manager->persist($vehicle);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%snew', $this->path));

        self::assertResponseStatusCodeSame(200);

        $this->client->submitForm('Save', [
            'availability[depart_date]' => '2024-05-01',
            'availability[return_date]' => '2024-05-10',
            'availability[price_per_day]' => 100,
            'availability[status]' => 1,
            'availability[vehicle]' => $vehicle->getId(),
        ]);

        self::assertResponseRedirects($this->path);

        self::assertSame(1, $this->availabilityRepository->count([]));
    }

    public function testShow(): void
    {
        $this->markTestIncomplete();
        $fixture = new Availability();
        $fixture->setDepartDate(new \DateTime('2024-05-01'));
        $fixture->setReturnDate(new \DateTime('2024-05-10'));
        $fixture->setPricePerDay(10);
        $fixture->setStatus(true);

        $vehicle = new Vehicle();
        $vehicle->setMake('Test Make');
        $vehicle->setModel('Test Model');
        $fixture->setVehicle($vehicle);

        $this->manager->persist($vehicle);
        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Availability');

        // Use assertions to check that the properties are properly displayed.
    }

    public function testEdit(): void
    {
        $this->markTestIncomplete();
        $fixture = new Availability();
        $fixture->setDepartDate(new \DateTime('2024-05-01'));
        $fixture->setReturnDate(new \DateTime('2024-05-10'));
        $fixture->setPricePerDay(10);
        $fixture->setStatus(true);

        $vehicle = new Vehicle();
        $vehicle->setMake('Test Make');
        $vehicle->setModel('Test Model');
        $fixture->setVehicle($vehicle);

        $this->manager->persist($vehicle);
        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s/edit', $this->path, $fixture->getId()));

        $this->client->submitForm('Update', [
            'availability[depart_date]' => '2024-05-02',
            'availability[return_date]' => '2024-05-11',
            'availability[price_per_day]' => 120,
            'availability[status]' => 1,
            'availability[vehicle]' => $vehicle->getId(),
        ]);

        self::assertResponseRedirects('/availability/');

        $fixture = $this->availabilityRepository->findAll();

        self::assertSame('2024-05-02', $fixture[0]->getDepart_date()->format('Y-m-d'));
        self::assertSame('2024-05-11', $fixture[0]->getReturn_date()->format('Y-m-d'));
        self::assertSame(120, $fixture[0]->getPrice_per_day());
        self::assertSame(true, $fixture[0]->getStatus());
        self::assertSame($vehicle, $fixture[0]->getVehicle());
    }

    public function testRemove(): void
    {
        $this->markTestIncomplete();
        $fixture = new Availability();
        $fixture->setDepartDate(new \DateTime('2024-05-01'));
        $fixture->setReturnDate(new \DateTime('2024-05-10'));
        $fixture->setPricePerDay(10);
        $fixture->setStatus(true);

        $vehicle = new Vehicle();
        $vehicle->setMake('Test Make');
        $vehicle->setModel('Test Model');
        $fixture->setVehicle($vehicle);

        $this->manager->persist($vehicle);
        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));
        $this->client->submitForm('Delete');

        self::assertResponseRedirects('/availability/');
        self::assertSame(0, $this->availabilityRepository->count([]));
    }
}

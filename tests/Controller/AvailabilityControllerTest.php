<?php

namespace App\Test\Controller;

use App\Entity\Availability;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class AvailabilityControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private EntityManagerInterface $manager;
    private EntityRepository $repository;
    private string $path = '/availability/';

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->manager = static::getContainer()->get('doctrine')->getManager();
        $this->repository = $this->manager->getRepository(Availability::class);

        foreach ($this->repository->findAll() as $object) {
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
        $this->markTestIncomplete();
        $this->client->request('GET', sprintf('%snew', $this->path));

        self::assertResponseStatusCodeSame(200);

        $this->client->submitForm('Save', [
            'availability[depart_date]' => 'Testing',
            'availability[return_date]' => 'Testing',
            'availability[price_per_day]' => 'Testing',
            'availability[status]' => 'Testing',
            'availability[vehicle]' => 'Testing',
        ]);

        self::assertResponseRedirects($this->path);

        self::assertSame(1, $this->repository->count([]));
    }

    public function testShow(): void
    {
        $this->markTestIncomplete();
        $fixture = new Availability();
        $fixture->setDepart_date('My Title');
        $fixture->setReturn_date('My Title');
        $fixture->setPrice_per_day('My Title');
        $fixture->setStatus('My Title');
        $fixture->setVehicle('My Title');

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
        $fixture->setDepart_date('Value');
        $fixture->setReturn_date('Value');
        $fixture->setPrice_per_day('Value');
        $fixture->setStatus('Value');
        $fixture->setVehicle('Value');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s/edit', $this->path, $fixture->getId()));

        $this->client->submitForm('Update', [
            'availability[depart_date]' => 'Something New',
            'availability[return_date]' => 'Something New',
            'availability[price_per_day]' => 'Something New',
            'availability[status]' => 'Something New',
            'availability[vehicle]' => 'Something New',
        ]);

        self::assertResponseRedirects('/availability/');

        $fixture = $this->repository->findAll();

        self::assertSame('Something New', $fixture[0]->getDepart_date());
        self::assertSame('Something New', $fixture[0]->getReturn_date());
        self::assertSame('Something New', $fixture[0]->getPrice_per_day());
        self::assertSame('Something New', $fixture[0]->getStatus());
        self::assertSame('Something New', $fixture[0]->getVehicle());
    }

    public function testRemove(): void
    {
        $this->markTestIncomplete();
        $fixture = new Availability();
        $fixture->setDepart_date('Value');
        $fixture->setReturn_date('Value');
        $fixture->setPrice_per_day('Value');
        $fixture->setStatus('Value');
        $fixture->setVehicle('Value');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));
        $this->client->submitForm('Delete');

        self::assertResponseRedirects('/availability/');
        self::assertSame(0, $this->repository->count([]));
    }
}

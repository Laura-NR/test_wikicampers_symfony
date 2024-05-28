<?php

namespace App\DataFixtures;

use App\Factory\VehicleFactory;
use App\Factory\AvailabilityFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        VehicleFactory::createMany(10); 
        AvailabilityFactory::createMany(30);

        $manager->flush();
    }
}

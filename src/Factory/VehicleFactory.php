<?php

namespace App\Factory;

use App\Entity\Vehicle;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;
use Zenstruck\Foundry\Instantiator;

/**
 * @extends ModelFactory<Vehicle>
 *
 * @method static Vehicle|Proxy createOne(array $attributes = [])
 * @method static Vehicle[]|Proxy[] createMany(int $number, array|callable $attributes = [])
 * @method static Vehicle|Proxy find(object|array|mixed $criteria)
 * @method static Vehicle|Proxy findOrCreate(array $attributes)
 * @method static Vehicle|Proxy first(string $sortedField = 'id')
 * @method static Vehicle|Proxy last(string $sortedField = 'id')
 * @method static Vehicle|Proxy random(array $attributes = [])
 * @method static Vehicle|Proxy randomOrCreate(array $attributes = [])
 * @method Vehicle|Proxy create(array|callable $attributes = [])
 */
final class VehicleFactory extends ModelFactory
{
    private const CAR_BRANDS = [
        'Toyota', 'Ford', 'Honda', 'Chevrolet', 'Nissan', 'BMW', 'Mercedes-Benz', 'Volkswagen', 'Audi', 'Hyundai'
    ];

    private const CAR_MODELS = [
        'Camry', 'F-150', 'Civic', 'Silverado', 'Altima', '3 Series', 'C-Class', 'Golf', 'A4', 'Elantra'
    ];

    protected function getDefaults(): array
    {
        return [
            'make' => self::faker()->randomElement(self::CAR_BRANDS),
            'model' => self::faker()->randomElement(self::CAR_MODELS),
        ];
    }

    protected static function getClass(): string
    {
        return Vehicle::class;
    }
}

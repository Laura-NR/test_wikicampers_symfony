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
 * @method static Vehicle[]|Proxy[] all()
 * @method static Vehicle[]|Proxy[] findBy(array $attributes)
 * @method static Vehicle[]|Proxy[] randomSet(int $number, array $attributes = [])
 * @method static Vehicle[]|Proxy[] randomRange(int $min, int $max, array $attributes = [])
 * @method Vehicle|Proxy create(array|callable $attributes = [])
 */
final class VehicleFactory extends ModelFactory
{
    protected function getDefaults(): array
    {
        return [
            'make' => self::faker()->company,
            'model' => self::faker()->word,
        ];
    }

    protected static function getClass(): string
    {
        return Vehicle::class;
    }
}

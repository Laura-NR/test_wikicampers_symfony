<?php

namespace App\Factory;

use App\Entity\Availability;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;
use Zenstruck\Foundry\Instantiator;

/**
 * @extends ModelFactory<Availability>
 *
 * @method static Availability|Proxy createOne(array $attributes = [])
 * @method static Availability[]|Proxy[] createMany(int $number, array|callable $attributes = [])
 * @method static Availability|Proxy find(object|array|mixed $criteria)
 * @method static Availability|Proxy findOrCreate(array $attributes)
 * @method static Availability|Proxy first(string $sortedField = 'id')
 * @method static Availability|Proxy last(string $sortedField = 'id')
 * @method static Availability|Proxy random(array $attributes = [])
 * @method static Availability|Proxy randomOrCreate(array $attributes = [])
 * @method static Availability[]|Proxy[] all()
 * @method static Availability[]|Proxy[] findBy(array $attributes)
 * @method static Availability[]|Proxy[] randomSet(int $number, array $attributes = [])
 * @method static Availability[]|Proxy[] randomRange(int $min, int $max, array $attributes = [])
 * @method Availability|Proxy create(array|callable $attributes = [])
 */
final class AvailabilityFactory extends ModelFactory
{
    protected function getDefaults(): array
    {
        return [
            'depart_date' => self::faker()->dateTimeBetween('-1 month', 'now'),
            'return_date' => self::faker()->dateTimeBetween('now', '+1 month'),
            'price_per_day' => self::faker()->randomFloat(2, 20, 100),
            'status' => self::faker()->boolean,
            'vehicle' => VehicleFactory::new(), // Create a new vehicle for each availability
        ];
    }

    protected static function getClass(): string
    {
        return Availability::class;
    }
}

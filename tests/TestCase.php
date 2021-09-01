<?php

namespace App\Tests;

use Domain\Model\OfferEntity;
use Domain\Model\OfferTypeValueObject;
use Domain\Model\PromoCodeAggregate;
use Domain\Model\PromoCodeEntity;
use Faker;
use PHPUnit\Framework\TestCase as BaseTestCase;

class TestCase extends BaseTestCase
{
    private $faker;

    public function setUp(): void
    {
        parent::setUp();

        $this->faker = Faker\Factory::create();
    }

    protected function mockPromoCodeAggregate(array $attributes = [])
    {
        $attributes += [
            'name' => uniqid('promo-code-'),
            'expiresAt' => $this->faker->date(),
            'discount' => $this->faker->randomFloat(),
            'offers' => [array_fill(0, $this->faker->numberBetween(1, 5), [])],
        ];

        return tap(new PromoCodeAggregate(), function ($aggregate) use ($attributes) {
            $aggregate->setRoot(tap(new PromoCodeEntity(), function ($promoCode) use ($attributes) {
                $promoCode->setName($attributes['name']);
                $promoCode->setExpirationTime(new \DateTimeImmutable($attributes['expiresAt']));
                $promoCode->setDiscountValue($attributes['discount']);
            }));

            $aggregate->setCompatibleOffers(
                array_map([$this, 'mockOfferEntity'], $attributes['offers'])
            );
        });
    }

    protected function mockOfferEntity(array $attributes = [])
    {
        $attributes += [
            'name' => uniqid('offer-'),
            'description' => "Description...",
            'type' => $this->faker->randomElement(['ELECTRICITY', 'GAS', 'WOOD']),
        ];

        return tap(new OfferEntity(), function ($offer) use ($attributes) {
            $offer->setName($attributes['name']);
            $offer->setDescription($attributes['description']);
            $offer->setType(OfferTypeValueObject::fromString($attributes['type']));
        });
    }
}

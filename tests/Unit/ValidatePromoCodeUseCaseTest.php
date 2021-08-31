<?php

namespace Tests\Unit;

use Domain\Contract\PromoCodeRepository;
use Domain\Model\OfferEntity;
use Domain\Model\OfferTypeValueObject;
use Domain\Model\PromoCodeAggregate;
use Domain\Model\PromoCodeEntity;
use Domain\UseCase\ValidatePromoCode\ValidatePromoCodeInteractor;
use Domain\UseCase\ValidatePromoCode\ValidatePromoCodeOutputPort;
use Mockery;
use PHPUnit\Framework\TestCase;

class ValidatePromoCodeUseCaseTest extends TestCase
{
    /**
     * @dataProvider promoCodeValidationDataProvider
     */
    public function testValidPromoCode(PromoCodeAggregate $promoCode, string $method)
    {
        $repository = Mockery::mock(PromoCodeRepository::class);
        $repository->shouldReceive('getPromoCodeFromCode')
            ->with($promoCode->getRoot()->getName())
            ->andReturn($promoCode);

        $presenter = Mockery::mock(ValidatePromoCodeOutputPort::class);
        $presenter->shouldReceive($method)
            ->with(Mockery::capture($presented));

        $interactor = new ValidatePromoCodeInteractor(
            $presenter,
            $repository,
        );

        $interactor->validate($promoCode->getRoot()->getName());

        $this->assertSame($promoCode, $presented);
    }

    public function promoCodeValidationDataProvider()
    {
        return [
            'valid promo code' => [
                'promoCode' => $this->mockPromoCodeAggregate([
                    'name' => 'promo-code-123',
                    'expiresAt' => 'tomorrow',
                    'discount' => 1.15,
                    'offers' => [[
                        'name' => 'offer-1',
                        'description' => 'Offer 1 description.',
                        'type' => OfferTypeValueObject::electricity(),
                    ],[
                        'name' => 'offer-2',
                        'description' => 'Offer 2 description.',
                        'type' => OfferTypeValueObject::gas(),
                    ]]
                ]),
                'method' => 'valid',
            ],

            'expired promo code' => [
                'promoCode' => $this->mockPromoCodeAggregate([
                    'name' => 'promo-code-123',
                    'expiresAt' => 'yesterday',
                    'discount' => 1.15,
                    'offers' => [[
                        'name' => 'offer-1',
                        'description' => 'Offer 1 description.',
                        'type' => OfferTypeValueObject::electricity(),
                    ],[
                        'name' => 'offer-2',
                        'description' => 'Offer 2 description.',
                        'type' => OfferTypeValueObject::gas(),
                    ]]
                ]),
                'method' => 'invalid',
            ],

            'promo code with no offers' => [
                'promoCode' => $this->mockPromoCodeAggregate([
                    'name' => 'promo-code-123',
                    'expiresAt' => 'tomorrow',
                    'discount' => 1.15,
                    'offers' => []
                ]),
                'method' => 'invalid',
            ],
        ];
    }

    private function mockPromoCodeAggregate(array $attributes)
    {
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

    private function mockOfferEntity(array $attributes)
    {
        return tap(new OfferEntity(), function ($offer) use ($attributes) {
            $offer->setName($attributes['name']);
            $offer->setDescription($attributes['description']);
            $offer->setType($attributes['type']);
        });
    }
}

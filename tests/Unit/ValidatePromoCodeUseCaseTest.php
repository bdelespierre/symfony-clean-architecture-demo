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
use App\Tests\TestCase;

class ValidatePromoCodeUseCaseTest extends TestCase
{
    /**
     * @dataProvider promoCodeValidationDataProvider
     */
    public function testInteractor($attributes, string $method)
    {
        $promoCode = $this->mockPromoCodeAggregate($attributes);

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

    public function testInteractorPromoCodeNotFound()
    {
        $repository = Mockery::mock(PromoCodeRepository::class);
        $repository->shouldReceive('getPromoCodeFromCode')
            ->andReturn(null);

        $presenter = Mockery::mock(ValidatePromoCodeOutputPort::class);
        $presenter->shouldReceive('notFound')
            ->with(Mockery::capture($presented));

        $interactor = new ValidatePromoCodeInteractor(
            $presenter,
            $repository,
        );

        $interactor->validate('promo-code-123');

        $this->assertSame('promo-code-123', $presented);
    }

    public function promoCodeValidationDataProvider()
    {
        return [
            'valid promo code' => [
                'attributes' => ['expiresAt' => 'tomorrow'],
                'method' => 'valid',
            ],

            'expired promo code' => [
                'attributes' => ['expiresAt' => 'yesterday'],
                'method' => 'expired',
            ],

            'promo code with no offers' => [
                'attributes' => ['expiresAt' => 'tomorrow', 'offers' => []],
                'method' => 'noCompatibleOffer',
            ],
        ];
    }
}

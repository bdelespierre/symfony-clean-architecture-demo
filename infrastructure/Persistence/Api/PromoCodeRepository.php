<?php

namespace Infrastructure\Persistence\Api;

use Domain\Contract\PromoCodeRepository as PromoCodeRepositoryContract;
use Domain\Contract\OfferRepository as OfferRepositoryContract;
use Domain\Model\PromoCodeAggregate;
use Domain\Model\PromoCodeEntity;
use Infrastructure\Service\Api\EkwaTestClient;

class PromoCodeRepository implements PromoCodeRepositoryContract
{
    public function __construct(
        private EkwaTestClient $client,
        private OfferRepositoryContract $offers,
    ) {
    }

    /**
     * @return \Generator<\Domain\Model\PromoCodeEntity>
     */
    public function listPromoCodes(): \Generator
    {
        $promoCodes = $this->client->promoCodeList();

        // @todo validate promo codes list

        foreach ($promoCodes as $promoCode) {
            yield $this->makePromoCodeFromObject($promoCode);
        }
    }

    public function getPromoCodeFromCode(string $code): ?PromoCodeAggregate
    {
        foreach ($this->listPromoCodes() as $promoCode) {
            if ($promoCode->getName() == $code) {
                return $this->makePromoCodeAggregateFromEntity($promoCode);
            }
        }

        return null;
    }

    private function makePromoCodeFromObject(\stdClass $object): PromoCodeEntity
    {
        return tap(new PromoCodeEntity(), function ($promoCode) use ($object) {
            $promoCode->setName($object->code);
            $promoCode->setDiscountValue($object->discountValue);
            $promoCode->setExpirationTime(new \DateTimeImmutable($object->endDate));
        });
    }

    private function makePromoCodeAggregateFromEntity(PromoCodeEntity $root): PromoCodeAggregate
    {
        return tap(new PromoCodeAggregate(), function ($promoCode) use ($root) {
            $promoCode->setRoot($root);
            $promoCode->setCompatibleOffers(
                $this->offers->getPromoCodeCompatibleOffers($root)
            );
        });
    }
}

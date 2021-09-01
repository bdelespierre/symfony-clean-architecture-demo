<?php

namespace Infrastructure\Persistence\Api;

use Domain\Contract\OfferRepository as OfferRepositoryContract;
use Domain\Model\OfferEntity;
use Domain\Model\OfferTypeValueObject;
use Domain\Model\PromoCodeEntity;
use Infrastructure\Service\Api\EkwaTestClient;

class OfferRepository implements OfferRepositoryContract
{
    public function __construct(
        private EkwaTestClient $client,
    ) {
    }

    /**
     * @return array<\Domain\Model\OfferEntity>
     */
    public function getPromoCodeCompatibleOffers(PromoCodeEntity $promoCode): array
    {
        $offers = $this->client->offerList();

        // @todo validate offers list

        return array_map(
            [$this, 'makeOfferFromObject'],
            array_filter(
                $offers,
                fn($offer) => in_array($promoCode->getName(), $offer->validPromoCodeList)
            )
        );
    }

    private function makeOfferFromObject(\stdClass $object): OfferEntity
    {
        return tap(new OfferEntity(), function ($offer) use ($object) {
            $offer->setName($object->offerName);
            $offer->setDescription($object->offerDescription);
            $offer->setType(OfferTypeValueObject::fromString($object->offerType));
        });
    }
}

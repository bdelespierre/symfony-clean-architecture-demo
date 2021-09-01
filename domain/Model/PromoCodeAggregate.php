<?php

namespace Domain\Model;

use Domain\Model\PromoCodeEntity;

class PromoCodeAggregate
{
    private PromoCodeEntity $promoCode;

    /**
     * @var array<\Domain\Model\OfferEntity>
     */
    private array $compatibleOffers;

    public function getRoot(): PromoCodeEntity
    {
        return $this->promoCode;
    }

    public function setRoot(PromoCodeEntity $code): void
    {
        $this->promoCode = $code;
    }

    /**
     * @return array<\Domain\Model\OfferEntity>
     */
    public function getCompatibleOffers(): array
    {
        return $this->compatibleOffers;
    }

    /**
     * @param array<\Domain\Model\OfferEntity> $offers
     */
    public function setCompatibleOffers(array $offers): void
    {
        $this->compatibleOffers = $offers;
    }

    public function hasCompatibleOffers(): bool
    {
        return (bool) count($this->compatibleOffers);
    }
}

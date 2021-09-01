<?php

namespace Domain\Contract;

use Domain\Model\PromoCodeEntity;

interface OfferRepository
{
    /**
     * @return array<\Domain\Model\OfferEntity>
     */
    public function getPromoCodeCompatibleOffers(PromoCodeEntity $promoCode): array;
}

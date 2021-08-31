<?php

namespace Domain\Contract;

use Domain\Model\PromoCodeAggregate;

interface PromoCodeRepository
{
    public function getPromoCodeFromCode(string $code): ?PromoCodeAggregate;
}

<?php

namespace Domain\UseCase\ValidatePromoCode;

use Domain\Contract\ViewModel;
use Domain\Model\PromoCodeAggregate;

interface ValidatePromoCodeOutputPort
{
    public function valid(PromoCodeAggregate $code): ViewModel;

    public function invalid(PromoCodeAggregate $code): ViewModel;

    public function notFound(string $code): ViewModel;
}

<?php

namespace Domain\UseCase\ValidatePromoCode;

use Domain\Contract\ViewModel;
use Domain\UseCase\ValidatePromoCode\ValidatePromoCodeRequestModel;

interface ValidatePromoCodeInputPort
{
    public function validate(string $code): ViewModel;
}

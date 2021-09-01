<?php

namespace Domain\UseCase\ValidatePromoCode;

use Domain\Contract\PromoCodeRepository;
use Domain\Contract\ViewModel;
use Domain\Model\PromoCodeEntity;
use Domain\UseCase\ValidatePromoCode\ValidatePromoCodeInputPort;
use Domain\UseCase\ValidatePromoCode\ValidatePromoCodeOutputPort;

class ValidatePromoCodeInteractor implements ValidatePromoCodeInputPort
{
    public function __construct(
        private ValidatePromoCodeOutputPort $output,
        private PromoCodeRepository $promoCodes,
    ) {
    }

    public function validate(string $code): ViewModel
    {
        $promoCode = $this->promoCodes->getPromoCodeFromCode($code);

        // no promo-code corresponds to the given code ¯\_(ツ)_/¯
        if (is_null($promoCode)) {
            return $this->output->notFound($code);
        }

        // for a promo-code to be valid, it needs to have compatible offers.
        if (!$promoCode->hasCompatibleOffers()) {
            return $this->output->noCompatibleOffer($promoCode);
        }

        // an expired promo-code is considered invalid.
        if ($promoCode->getRoot()->isExpired()) {
            return $this->output->expired($promoCode);
        }

        return $this->output->valid($promoCode);
    }
}

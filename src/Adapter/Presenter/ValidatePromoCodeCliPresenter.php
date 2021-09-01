<?php

namespace App\Adapter\Presenter;

use App\Adapter\ViewModel\CliViewModel;
use Domain\Contract\ViewModel;
use Domain\Model\OfferEntity;
use Domain\Model\PromoCodeAggregate;
use Domain\UseCase\ValidatePromoCode\ValidatePromoCodeOutputPort;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Output\OutputInterface;

class ValidatePromoCodeCliPresenter implements ValidatePromoCodeOutputPort
{
    public function valid(PromoCodeAggregate $promoCode): ViewModel
    {
        return new CliViewModel(function (OutputInterface $output) use ($promoCode): int {
            $expires = $promoCode->getRoot()->getExpirationTime();

            $output->writeln((string) json_encode([
                'promoCode' => $promoCode->getRoot()->getName(),
                'endDate' => $expires ? $expires->format('Y-m-d') : null,
                'discountValue' => $promoCode->getRoot()->getDiscountValue(),
                'compatibleOfferList' => array_map(fn(OfferEntity $offer) => [
                    'name' => $offer->getName(),
                    'type' => (string) $offer->getType(),
                ], $promoCode->getCompatibleOffers())
            ]));

            return Command::SUCCESS;
        });
    }

    public function noCompatibleOffer(PromoCodeAggregate $promoCode): ViewModel
    {
        return $this->invalid("No compatible offer found for code '{$promoCode->getRoot()->getName()}'");
    }

    public function expired(PromoCodeAggregate $promoCode): ViewModel
    {
        return $this->invalid("Promo code '{$promoCode->getRoot()->getName()}' has expired");
    }

    private function invalid(string $reason): ViewModel
    {
        return new CliViewModel(function (OutputInterface $output) use ($reason): int {
            $this->getErrorOutput($output)->writeln("<comment>[invalid]</> {$reason}");
            return Command::FAILURE;
        });
    }

    public function notFound(string $code): ViewModel
    {
        return new CliViewModel(function (OutputInterface $output) use ($code): int {
            $this->getErrorOutput($output)->writeln("<error>[error]</> code '{$code}' was not found");
            return Command::FAILURE;
        });
    }

    private function getErrorOutput(OutputInterface $output): OutputInterface
    {
        return method_exists($output, 'getErrorOutput') ? $output->getErrorOutput() : $output;
    }
}

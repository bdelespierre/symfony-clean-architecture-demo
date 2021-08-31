<?php

namespace Domain\Model;

final class OfferTypeValueObject
{
    private function __construct(
        private string $value,
    ) {
    }

    public function __toString()
    {
        return (string) $this->value;
    }

    public function isEqualTo(self $type): bool
    {
        return $this->value == $type->value;
    }

    public static function electricity(): self
    {
        return new self("ELECTRICITY");
    }

    public static function gas(): self
    {
        return new self("GAS");
    }

    public static function wood(): self
    {
        return new self("WOOD");
    }
}

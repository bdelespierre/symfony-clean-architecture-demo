<?php

namespace Domain\Model;

final class OfferTypeValueObject
{
    private string $value;

    private function __construct(string $value)
    {
        $this->value = $value;
    }

    public function __toString()
    {
        return (string) $this->value;
    }

    public function isEqualTo(self $type): bool
    {
        return $this->value == $type->value;
    }

    public static function fromString(string $value): self
    {
        switch (strtoupper($value)) {
            case "ELECTRICITY":
                return self::electricity();

            case "GAS":
                return self::gas();

            case "WOOD":
                return self::wood();

            default:
                throw new \DomainException("Invalid offer type '{$value}'");
        }
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

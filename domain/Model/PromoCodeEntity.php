<?php

namespace Domain\Model;

class PromoCodeEntity
{
    private string $name;
    private \DateTimeImmutable $expiresAt;
    private float $discount;

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getExpirationTime(): ?\DateTimeImmutable
    {
        return $this->expiresAt;
    }

    public function setExpirationTime(\DateTimeImmutable $time): void
    {
        $this->expiresAt = $time;
    }

    public function isExpired(): bool
    {
        return $this->getExpirationTime() <= new \DateTime();
    }

    public function getDiscountValue(): ?float
    {
        return $this->discount;
    }

    public function setDiscountValue(float $value): void
    {
        $this->discount = $value;
    }
}

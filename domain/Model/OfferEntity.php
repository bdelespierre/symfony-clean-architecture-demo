<?php

namespace Domain\Model;

use Domain\Model\OfferTypeValueObject;

class OfferEntity
{
    private string $name;
    private string $description;
    private OfferTypeValueObject $type;

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    public function getType(): OfferTypeValueObject
    {
        return $this->type;
    }

    public function setType(OfferTypeValueObject $type): void
    {
        $this->type = $type;
    }
}

<?php

namespace HubspotTest\Partner\Domain\Model\Country;

use JsonSerializable;

final class CountryCollection implements JsonSerializable
{
    private $countries;

    public function __construct()
    {
        $this->countries = [];
    }

    public function addCountry(Country $a_country): void
    {
        $this->countries[$a_country->name()] = $a_country;
    }

    public function countries(): array
    {
        return $this->countries;
    }

    public function jsonSerialize()
    {
        return ['countries' => \array_values($this->countries)];
    }
}

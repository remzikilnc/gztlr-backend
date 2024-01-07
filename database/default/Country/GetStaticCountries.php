<?php

namespace Database\Default\Country;

class GetStaticCountries
{
    private array $citiesList;

    public function __construct()
    {
        $this->citiesList = include __DIR__ . '/countries.php';
    }

    public function getCities(): array
    {
        return $this->citiesList;
    }
}

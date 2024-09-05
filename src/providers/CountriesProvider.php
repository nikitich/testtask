<?php

namespace App\providers;

use App\providers\interfaces\CountriesProviderInterface;

readonly class CountriesProvider implements CountriesProviderInterface
{
    public function getEUCountriesCodes(): array
    {
        return require __DIR__ . '/../../data/eu_countries_codes.php';
    }
}
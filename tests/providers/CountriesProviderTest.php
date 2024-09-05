<?php

namespace Tests\providers;

use App\providers\CountriesProvider;
use PHPUnit\Framework\TestCase;

class CountriesProviderTest extends TestCase
{
    private CountriesProvider $countriesProvider;

    protected function setUp(): void
    {
        $this->countriesProvider = new CountriesProvider();
    }

    public function testGetEUCountriesCodes(): void
    {
        $this->assertIsArray($this->countriesProvider->getEUCountriesCodes());
        $this->assertGreaterThan(1, count($this->countriesProvider->getEUCountriesCodes()));
        foreach ($this->countriesProvider->getEUCountriesCodes() as $countryCode) {
            $this->assertIsString($countryCode);
            $this->assertEquals(2, strlen($countryCode));
        }
    }
}
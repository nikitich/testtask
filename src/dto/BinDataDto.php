<?php

namespace App\dto;

readonly class BinDataDto
{
    public string $countryAlpha2;

    public function __construct(string $countryAlpha2)
    {
        $this->countryAlpha2 = $countryAlpha2;
    }
}
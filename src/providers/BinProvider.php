<?php

namespace App\providers;

use App\dto\BinDataDto;
use App\providers\interfaces\BinProviderInterface;
use JsonException;
use RuntimeException;

readonly class BinProvider implements BinProviderInterface
{
    /**
     * @throws JsonException
     * @throws RuntimeException
     */
    public function getBinData(int $bin): BinDataDto
    {
        $rawBinData = $this->fetchBinData($bin);
        $binData = json_decode($rawBinData, false, 512, JSON_THROW_ON_ERROR);

        return new BinDataDto($binData->country->alpha2 ?? '');
    }

    /**
     * @throws RuntimeException
     */
    protected function fetchBinData(int $bin): string
    {
        $binLookupApiUrl = getenv('BIN_LOOKUP_API_URL');
        $binResultsResponse = file_get_contents($binLookupApiUrl . "/$bin");

        if (! $binResultsResponse) {
            throw new RuntimeException('Error fetching BIN data');
        }

        return $binResultsResponse;
    }
}
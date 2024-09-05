<?php

namespace App\providers;

use App\providers\interfaces\ExchangeRateProviderInterface;
use JsonException;
use RuntimeException;

readonly class ExchangeRateProvider implements ExchangeRateProviderInterface
{
    /**
     * @throws RuntimeException
     * @throws JsonException
     */
    public function getRate($currency): float
    {
        $baseCurrency = getenv('BASE_CURRENCY');

        if ($currency !== $baseCurrency) {
            $ratesData = $this->fetchRatesData();
            $rates = @json_decode($ratesData, true, 512, JSON_THROW_ON_ERROR)['rates'] ?? null;

            if (! $rates) {
                throw new RuntimeException('Error decoding exchange rates');
            }

            return $rates[$currency] ?? 0;
        }

        return 1;
    }

    protected function fetchRatesData(): string
    {
        $exchangeRatesApiUrl = getenv('EXCHANGE_RATES_API_URL');
        $exchangeRatesApiKey = getenv('EXCHANGE_RATES_API_KEY');

        $response = file_get_contents(
            "{$exchangeRatesApiUrl}?access_key={$exchangeRatesApiKey}",
            false,
            stream_context_create([
                'http' => ['ignore_errors' => true],
            ])
        );

        if ($response === false) {
            throw new RuntimeException('Error fetching exchange rates');
        }

        return $response;
    }
}
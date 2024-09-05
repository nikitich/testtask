<?php

namespace App\services;

use App\dto\TransactionDto;
use App\providers\interfaces\BinProviderInterface;
use App\providers\interfaces\CountriesProviderInterface;
use App\providers\interfaces\ExchangeRateProviderInterface;
use App\services\interfaces\CommissionCalculatorServiceInterface;
use JsonException;

readonly class CommissionCalculatorService implements CommissionCalculatorServiceInterface
{
    public function __construct(
        private BinProviderInterface $binProvider,
        private ExchangeRateProviderInterface $exchangeRateProvider,
        private CountriesProviderInterface $countriesProvider,
    ) {
    }

    /**
     * @throws JsonException
     */
    public function calculate(array $transactions): array
    {
        $commissions = [];

        foreach ($transactions as $transaction) {
            /** @var TransactionDto $transaction */

            $bin = $transaction->bin;
            $amount = $transaction->amount;
            $currency = $transaction->currency;

            $rate = $this->exchangeRateProvider->getRate($currency);
            $binData = $this->binProvider->getBinData($bin);

            $isEu = $this->isEuCountry($binData->countryAlpha2);

            $amntFixed = bcdiv((string)$amount, (string)$rate, 6);
            $commissionRate = $isEu ? '0.01' : '0.02';
            $commission = bcmul($amntFixed, $commissionRate, 6);

            $commissions[] = round($commission, 2);
        }

        return $commissions;
    }

    private function isEuCountry(string $countryAlpha2): bool
    {
        return in_array(
            $countryAlpha2,
            $this->countriesProvider->getEUCountriesCodes(),
            true
        );
    }
}

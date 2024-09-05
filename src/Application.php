<?php

namespace App;

use App\providers\interfaces\InputFileProviderInterface;
use App\services\interfaces\CommissionCalculatorServiceInterface;
use App\services\interfaces\TransactionsParseServiceInterface;
use JsonException;

readonly class Application
{
    public function __construct(
        private InputFileProviderInterface $inputFileProvider,
        private TransactionsParseServiceInterface $transactionsParseService,
        private CommissionCalculatorServiceInterface $commissionCalculatorService,
    ) {
    }

    /**
     * @throws JsonException
     */
    public function run(string $fileName): void
    {
        $transactionsData = $this->inputFileProvider->fetchData($fileName);
        $transactions = $this->transactionsParseService->parse($transactionsData);
        $commisonRates = $this->commissionCalculatorService->calculate($transactions);

        $this->render($commisonRates);
    }

    private function render(array $commissions): void
    {
        foreach ($commissions as $commission) {
            echo $commission . PHP_EOL;
        }
    }
}
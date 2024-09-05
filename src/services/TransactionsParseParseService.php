<?php

namespace App\services;

use App\dto\TransactionDto;
use App\services\interfaces\TransactionsParseServiceInterface;
use JsonException;
use RuntimeException;

readonly class TransactionsParseParseService implements TransactionsParseServiceInterface
{
    /**
     * @throws JsonException
     * @throws RuntimeException
     */
    public function parse(string $inputData): array
    {
        return array_map(
            static function (string $transaction) {
                $transaction = json_decode($transaction, true, 512, JSON_THROW_ON_ERROR);
                return new TransactionDto(
                    $transaction['bin'] ?? 0,
                    $transaction['amount'] ?? '0.0',
                    $transaction['currency'] ?? ''
                );
            },
            explode("\n", $inputData)
        );
    }
}
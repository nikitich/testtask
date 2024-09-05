<?php

namespace Tests\services;

use App\dto\TransactionDto;
use App\services\TransactionsParseParseService;
use JsonException;
use PHPUnit\Framework\TestCase;

class TransactionsParseParseServiceTest extends TestCase
{
    private TransactionsParseParseService $transactionsParseService;

    protected function setUp(): void
    {
        $this->transactionsParseService = new TransactionsParseParseService();
    }

    /**
     * @throws JsonException
     */
    public function testParseValidInput(): void
    {
        $inputData = '{"bin":"45717360","amount":100.00,"currency":"USD"}' . "\n" .
            '{"bin":"516793","amount":50.00,"currency":"EUR"}';

        $result = $this->transactionsParseService->parse($inputData);

        $expected = [
            new TransactionDto('45717360', 100.00, 'USD'),
            new TransactionDto('516793', 50.00, 'EUR')
        ];

        $this->assertEquals($expected, $result);
    }

    /**
     * @throws JsonException
     */
    public function testParseInvalidJsonThrowsException(): void
    {
        $this->expectException(JsonException::class);

        $inputData = '{"bin":"45717360","amount":100.00,"currency":"USD"' . "\n" .
            '{"bin":"516793","amount":50.00,"currency":"EUR"}';

        $this->transactionsParseService->parse($inputData);
    }

    /**
     * @throws JsonException
     */
    public function testParseEmptyInput(): void
    {
        $this->expectException(JsonException::class);

        $inputData = '';

        $this->transactionsParseService->parse($inputData);
    }
}
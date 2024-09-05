<?php

namespace Tests\services;

use App\dto\BinDataDto;
use App\dto\TransactionDto;
use App\providers\interfaces\BinProviderInterface;
use App\providers\interfaces\CountriesProviderInterface;
use App\providers\interfaces\ExchangeRateProviderInterface;
use App\services\CommissionCalculatorService;
use JsonException;
use PHPUnit\Framework\MockObject\Exception;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class CommissionCalculatorServiceTest extends TestCase
{
    private MockObject|BinProviderInterface $binProviderMock;
    private MockObject|ExchangeRateProviderInterface $exchangeRateProviderMock;
    private MockObject|CountriesProviderInterface $countriesProviderMock;
    private CommissionCalculatorService $commissionCalculatorService;

    /**
     * @throws Exception
     */
    protected function setUp(): void
    {
        $this->binProviderMock = $this->createMock(BinProviderInterface::class);
        $this->exchangeRateProviderMock = $this->createMock(ExchangeRateProviderInterface::class);
        $this->countriesProviderMock = $this->createMock(CountriesProviderInterface::class);

        $this->commissionCalculatorService = new CommissionCalculatorService(
            $this->binProviderMock,
            $this->exchangeRateProviderMock,
            $this->countriesProviderMock
        );
    }

    /**
     * @throws JsonException
     */
    public function testCalculateCommissionForEuCountry(): void
    {
        $transaction = new TransactionDto('45717360', 100.00, 'USD');

        $this->exchangeRateProviderMock
            ->method('getRate')
            ->with('USD')
            ->willReturn(1.2);

        $this->binProviderMock
            ->method('getBinData')
            ->with('45717360')
            ->willReturn(new BinDataDto('AT'));

        $this->countriesProviderMock
            ->method('getEUCountriesCodes')
            ->willReturn(['AT', 'DE', 'FR']);

        $result = $this->commissionCalculatorService->calculate([$transaction]);

        $this->assertEquals(['0.83'], $result);
    }

    /**
     * @throws JsonException
     */
    public function testCalculateCommissionForNonEuCountry(): void
    {
        $transaction = new TransactionDto('45717360', 100.00, 'USD');

        $this->exchangeRateProviderMock
            ->method('getRate')
            ->with('USD')
            ->willReturn(1.2);

        $this->binProviderMock
            ->method('getBinData')
            ->with('45717360')
            ->willReturn(new BinDataDto('US'));

        $this->countriesProviderMock
            ->method('getEUCountriesCodes')
            ->willReturn(['AT', 'DE', 'FR']);

        $result = $this->commissionCalculatorService->calculate([$transaction]);

        $this->assertEquals(['1.67'], $result);
    }
}
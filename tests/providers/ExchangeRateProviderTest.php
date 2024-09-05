<?php

namespace Tests\providers;

use App\providers\ExchangeRateProvider;
use JsonException;
use PHPUnit\Framework\TestCase;
use RuntimeException;

class ExchangeRateProviderTest extends TestCase
{
    private ExchangeRateProvider $exchangeRateProvider;

    protected function setUp(): void
    {
        $this->exchangeRateProvider = $this->getMockBuilder(ExchangeRateProvider::class)
            ->onlyMethods(['fetchRatesData'])
            ->getMock();
    }

    /**
     * @throws JsonException
     */
    public function testGetRateWithValidCurrency(): void
    {
        $currency = 'USD';
        $apiResponse = '{"rates":{"USD":1.1497,"JPY":129.53}}';

        $this->exchangeRateProvider->expects($this->once())
            ->method('fetchRatesData')
            ->willReturn($apiResponse);

        $rate = $this->exchangeRateProvider->getRate($currency);

        $this->assertEquals(1.1497, $rate);
    }

    /**
     * @throws JsonException
     */
    public function testGetRateWithBaseCurrency(): void
    {
        $currency = 'EUR';
        putenv('BASE_CURRENCY=EUR');

        $rate = $this->exchangeRateProvider->getRate($currency);

        $this->assertEquals(1, $rate);
    }


    /**
     * @throws JsonException
     */
    public function testGetRateThrowsJsonExceptionOnInvalidJson(): void
    {
        $this->expectException(JsonException::class);

        $currency = 'USD';
        $invalidJson = '{"rates":{"USD":1.1497';

        $this->exchangeRateProvider->expects($this->once())
            ->method('fetchRatesData')
            ->willReturn($invalidJson);

        $this->exchangeRateProvider->getRate($currency);
    }

    /**
     * @throws JsonException
     */
    public function testGetRateThrowsRuntimeExceptionOnFetchFailure(): void
    {
        $this->expectException(RuntimeException::class);

        $currency = 'USD';

        $this->exchangeRateProvider->expects($this->once())
            ->method('fetchRatesData')
            ->willThrowException(new RuntimeException());

        $this->exchangeRateProvider->getRate($currency);
    }
}
<?php

namespace Tests\providers;

use App\providers\BinProvider;
use JsonException;
use PHPUnit\Framework\TestCase;
use RuntimeException;

class BinProviderTest extends TestCase
{
    private BinProvider $binProvider;

    protected function setUp(): void
    {
        $this->binProvider = $this->getMockBuilder(BinProvider::class)
            ->onlyMethods(['fetchBinData'])
            ->getMock();
    }

    /**
     * @throws JsonException
     */
    public function testGetBinData(): void
    {
        $bin = 45717360;
        $apiResponse = '{"country":{"alpha2":"US"}}';

        $this->binProvider->expects($this->once())
            ->method('fetchBinData')
            ->with($bin)
            ->willReturn($apiResponse);

        $binData = $this->binProvider->getBinData($bin);

        $this->assertEquals('US', $binData->countryAlpha2);
    }

    /**
     * @throws JsonException
     */
    public function testGetBinDataThrowsJsonExceptionOnInvalidJson(): void
    {
        $this->expectException(JsonException::class);

        $bin = 45717360;
        $invalidJson = '{"country":{"alpha2":"US"';

        $this->binProvider->expects($this->once())
            ->method('fetchBinData')
            ->with($bin)
            ->willReturn($invalidJson);

        $this->binProvider->getBinData($bin);
    }

    /**
     * @throws JsonException
     */
    public function testGetBinDataThrowsRuntimeExceptionOnFetchFailure(): void
    {
        $this->expectException(RuntimeException::class);

        $bin = 45717360;

        $this->binProvider->expects($this->once())
            ->method('fetchBinData')
            ->with($bin)
            ->willThrowException(new RuntimeException());

        $this->binProvider->getBinData($bin);
    }
}
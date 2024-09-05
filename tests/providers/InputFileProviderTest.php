<?php

namespace Tests\providers;

use App\providers\InputFileProvider;
use PHPUnit\Framework\TestCase;
use RuntimeException;

class InputFileProviderTest extends TestCase
{
    private InputFileProvider $inputFileProvider;

    protected function setUp(): void
    {
        $this->inputFileProvider = new InputFileProvider();
    }

    public function testFetchDataReturnsContent(): void
    {
        $filePath = __DIR__ . '/testfile.txt';
        file_put_contents($filePath, 'test content');

        $result = $this->inputFileProvider->fetchData($filePath);

        $this->assertEquals('test content', $result);

        unlink($filePath);
    }

    public function testFetchDataThrowsRuntimeExceptionOnFailure(): void
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Error reading the input file');

        $invalidFilePath = __DIR__ . '/nonexistentfile.txt';

        $this->inputFileProvider->fetchData($invalidFilePath);
    }
}
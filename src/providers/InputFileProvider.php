<?php

namespace App\providers;

use App\providers\interfaces\InputFileProviderInterface;
use RuntimeException;

readonly class InputFileProvider implements InputFileProviderInterface
{
    /**
     * @throws RuntimeException
     */
    public function fetchData(string $filePath): string
    {
        $inputData = file_get_contents($filePath);

        if ($inputData === false) {
            throw new RuntimeException('Error reading the input file');
        }

        return $inputData;
    }
}
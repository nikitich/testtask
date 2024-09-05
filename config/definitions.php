<?php

use App\providers\BinProvider;
use App\providers\CountriesProvider;
use App\providers\ExchangeRateProvider;
use App\providers\InputFileProvider;
use App\providers\interfaces\BinProviderInterface;
use App\providers\interfaces\CountriesProviderInterface;
use App\providers\interfaces\ExchangeRateProviderInterface;
use App\providers\interfaces\InputFileProviderInterface;
use App\services\CommissionCalculatorService;
use App\services\TransactionsParseService;
use App\services\interfaces\CommissionCalculatorServiceInterface;
use App\services\interfaces\TransactionsParseServiceInterface;

return [
    BinProviderInterface::class => DI\autowire(BinProvider::class),
    ExchangeRateProviderInterface::class => DI\autowire(ExchangeRateProvider::class),
    CountriesProviderInterface::class => DI\autowire(CountriesProvider::class),
    InputFileProviderInterface::class => DI\autowire(InputFileProvider::class),
    CommissionCalculatorServiceInterface::class => DI\autowire(CommissionCalculatorService::class),
    TransactionsParseServiceInterface::class => DI\autowire(TransactionsParseService::class),
];
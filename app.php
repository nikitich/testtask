<?php

require_once __DIR__ . '/vendor/autoload.php';

use App\Application;
use DI\ContainerBuilder;
use Symfony\Component\Dotenv\Dotenv;

$dotenv = new Dotenv();
$dotenv->usePutenv()->load(__DIR__ . '/.env');

$containerBuilder = new ContainerBuilder();

$containerBuilder
    ->useAutowiring(true)
    ->useAttributes(true)
    ->addDefinitions(require __DIR__ . '/config/definitions.php');

try {
    $container = $containerBuilder->build();
    $application = $container->get(Application::class);
    $application->run($argv[1]);
} catch (Throwable $exception) {
    // Here might be implemented any error handling logic
    "Error occured";
    exit(1);
}
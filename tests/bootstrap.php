<?php

use Symfony\Component\Dotenv\Dotenv;

require __DIR__ . '/../vendor/autoload.php';

$configFile = __DIR__ . '/../config/bootstrap.php';
if (is_readable($configFile)) {
    require $configFile;
} elseif (method_exists(Dotenv::class, 'bootEnv')) {
    (new Dotenv())->bootEnv(__DIR__ . '/../.env');
}

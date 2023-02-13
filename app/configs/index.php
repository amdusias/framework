<?php

use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(dirname(dirname(__DIR__)));
$dotenv->load();

return [
    'env' => $_ENV['APP_ENV'],
    'debug' => $_ENV['APP_DEBUG']
];
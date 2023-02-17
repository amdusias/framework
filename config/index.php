<?php

use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(dirname(__DIR__));
$dotenv->load();

$components = [];
foreach (scandir(__DIR__ . '/components') AS $fileName) {
    if ($fileName !== '.' && $fileName !== '..') {
        $components[substr($fileName, 0, -4)] = require __DIR__ . '/components/' . $fileName;
    }
}

return [
    'env' => $_ENV['APP_ENV'],
    'debug' => $_ENV['APP_DEBUG'],

    'errorController' => '\App\Controllers\DefaultController',
    'errorAction' => 'error',

    'components' => $components
];
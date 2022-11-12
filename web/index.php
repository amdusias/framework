<?php

// задаем строгую типизацию
declare(strict_types=1);

use App\Kernel,
    Zend\Diactoros\ServerRequestFactory;

if (PHP_VERSION_ID < 74000) {
    exit('Для работы фреймворка требуется PHP версия не меньше 7.4. У вас: ' . PHP_VERSION);
}

// подключаем автозагрузку классов
require __DIR__ . '/../app/__autoload.php';

// возвращаем ядро
$kernel = new Kernel('dev', true);
$request = ServerRequestFactory::fromGlobals($_SERVER, $_GET, $_POST, $_COOKIE, $_FILES);
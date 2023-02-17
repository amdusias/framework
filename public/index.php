<?php

declare(strict_types=1);

use Framework\Framework;
use Zend\Diactoros\ServerRequestFactory;

include __DIR__ . '/../app/__autoload.php';

$config = require_once __DIR__ . '/../config/index.php';

$request = ServerRequestFactory::fromGlobals($_SERVER, $_GET, $_POST, $_COOKIE, $_FILES);

Framework::getInstance($config)->run($request);
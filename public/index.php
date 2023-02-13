<?php

declare(strict_types=1);

use Framework\Framework;

include __DIR__ . '/../app/__autoload.php';

$config = require_once __DIR__ . '/../app/configs/index.php';

Framework::getInstance($config)->run();
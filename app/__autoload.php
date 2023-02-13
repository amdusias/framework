<?php

use Framework\Base\Autoload;

require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../framework/base/Autoload.php';

spl_autoload_register(['\Framework\Base\Autoload', 'loader'], true, false);

Autoload::setAlias('Framework', __DIR__ . '/../framework');
Autoload::setAlias('App', __DIR__);
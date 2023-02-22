<?php

return [
    'class' => '\Framework\Db\Drivers\Connection',
    'arguments' => [
        'dsn' => 'mysql:host=localhost;dbname=framework',
        'config' => [
            'username' => 'root',
            'password' => 'root'
        ],
        'options' => [PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES UTF8']
    ]
];
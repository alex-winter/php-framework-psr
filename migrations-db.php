<?php

use Doctrine\DBAL\DriverManager;

require_once __DIR__ . '/vendor/autoload.php';

$connectionParams = [
    'dbname' => 'app',
    'user' => 'appuser',
    'password' => 'apppassword',
    'host' => 'database',
    'driver' => 'pdo_mysql',
];

$connection = DriverManager::getConnection($connectionParams);

return $connection;
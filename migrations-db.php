<?php

use Doctrine\DBAL\DriverManager;

require_once __DIR__ . '/vendor/autoload.php';

$config = require_once __DIR__ . '/bin/config.php';

$connection = DriverManager::getConnection($config['database']);

return $connection;
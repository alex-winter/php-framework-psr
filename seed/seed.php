<?php

$config = require_once __DIR__ . '/../bin/config.php';

$user = $config['database']['user'];
$pass = $config['database']['password'];
$host = $config['database']['host'];
$port = $config['database']['port'];
$db = $config['database']['dbname'];
$charset = $config['database']['charset'];

$dsn = "mysql:host=$host;port=$port;dbname=$db;charset=$charset";

$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, // throw exceptions
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,       // return associative arrays
    PDO::ATTR_EMULATE_PREPARES   => false,                  // use native prepares if possible
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);

    $sql = file_get_contents(__DIR__ . '/items.sql');
    if ($sql === false) {
        throw new Exception("Failed to read SQL file.");
    }

    $pdo->exec($sql);

    echo "Connected to MySQL successfully!\n";
} catch (\PDOException $e) {
    throw new \PDOException($e->getMessage(), (int)$e->getCode());
}


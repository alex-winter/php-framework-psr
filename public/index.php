<?php

require_once __DIR__ . '/../vendor/autoload.php';

use AlexWinter\Framework\App;
use PublicTests\Middleware\TestMiddleware;
use PublicTests\IndexHandler;

$app = App::make();

$app
    ->get('/', IndexHandler::class)
    ->addMiddleware(new TestMiddleware());

$app->run();

<?php

require_once __DIR__ . '/../vendor/autoload.php';

use PublicTests\Middleware\TestMiddleware;
use PublicTests\IndexHandler;
use Slim\Factory\AppFactory;
use Pimple\Container;
use Pimple\Psr11\Container as PsrContainer;

$app = AppFactory::create(
    container: new PsrContainer(new Container())
);

$container = $app->getContainer();

$app->get('/', IndexHandler::class);

$app->run();
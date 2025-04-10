<?php

require_once __DIR__ . '/../vendor/autoload.php';

use App\Container;
use Slim\Factory\AppFactory;

$app = AppFactory::create(
    container: new Container()
);

$container = $app->getContainer();

$app->get('/', $container->requestHandlerIndex);

$app->run();
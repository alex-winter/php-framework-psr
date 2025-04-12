<?php

require_once __DIR__ . '/../vendor/autoload.php';

use App\Container;
use App\Route;
use Slim\Factory\AppFactory;

$app = AppFactory::create(
    container: $container = new Container()
);

$route = new Route($app);

$loadConfig = require_once __DIR__ . '/../bin/load-config.php';

foreach ($loadConfig['request-handlers'] as $requestHandlerClass) {
    $container->addRequestHanlder($requestHandlerClass);
}

require_once __DIR__ . '/../bin/routes.php';

$app->run();
<?php

require_once __DIR__ . '/../vendor/autoload.php';

use App\Container;
use App\Functions\Load;
use Slim\Factory\AppFactory;

$app = AppFactory::create(
    container: new Container()
);

Load::files(__DIR__ . '/../bin/request-handlers', $app);
Load::files(__DIR__ . '/../bin/routes', $app);

$app->run();
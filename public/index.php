<?php

require_once __DIR__ . '/../vendor/autoload.php';

use AlexWinter\Framework\App;
use AlexWinter\Framework\Response;
use AlexWinter\Framework\ServerRequest;
use PublicTests\IndexHandler;

$app = App::make();

$app->get('/', IndexHandler::class);

$app->run(ServerRequest::fromGlobals(), new Response());

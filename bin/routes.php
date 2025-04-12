<?php
/** @var App\Route $route */

use App\CreateItemHandler;
use App\RequestHandler\IndexRequestHandler;

$route->get('/', IndexRequestHandler::class);

$route->post('/', CreateItemHandler::class);
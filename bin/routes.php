<?php
/** @var App\Route $route */

use App\RequestHandler\CreateItemHandler;
use App\RequestHandler\GetAllItemsHandler;
use App\RequestHandler\IndexRequestHandler;

$route->get('/', IndexRequestHandler::class);

$route->get('/items', GetAllItemsHandler::class);

$route->post('/item', CreateItemHandler::class);
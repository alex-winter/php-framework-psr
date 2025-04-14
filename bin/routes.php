<?php
/** @var App\Route $route */

use App\Middleware\Item\ParseRequestedData;
use App\RequestHandler\CreateItemHandler;
use App\RequestHandler\GetAllItemsHandler;
use App\RequestHandler\IndexRequestHandler;

use function App\functions\get;

$route->get('/', IndexRequestHandler::class);

$route->get('/items', GetAllItemsHandler::class);

$route->post('/item', CreateItemHandler::class)
    ->add(get(ParseRequestedData::class));
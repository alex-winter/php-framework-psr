<?php

use Psr\Http\Message\ServerRequestInterface;
use App\IndexHandler;

/**
 * @var App\Container $container
 */
$container->requestHandlerIndex = function () {
    return fn (ServerRequestInterface $request) => new IndexHandler()->handle($request);
};
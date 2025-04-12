<?php

namespace App;

use App\RequestHandler\IndexRequestHandler;
use Pimple\Container as PimpleContainer;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ServerRequestInterface;

final class Container implements ContainerInterface
{
    function __construct(
        private readonly \ArrayAccess $arrayAccessContainer = new PimpleContainer(),
    ) {
    }

    public function get(string $id): mixed
    {
        return $this->arrayAccessContainer[$id];
    }

    public function has(string $id): bool
    {
        return isset($this->arrayAccessContainer[$id]);
    }

    public function setClass(string $class): void
    {
        $this->arrayAccessContainer[$class] = $class;
    }

    public function addRequestHanlder(string $class): void
    {
        $this->arrayAccessContainer[$class] = fn () => function (ServerRequestInterface $request) use ($class) {
            $instance = new $class();

            return $instance->handle($request);
        };
    }

    public function getRequestHandlerIndex(string $class): callable
    {
        return $this->get($class);
    }
}
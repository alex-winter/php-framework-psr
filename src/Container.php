<?php

namespace App;

use Pimple\Container as PimpleContainer;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

final class Container implements ContainerInterface
{
    private static self $instance;

    function __construct(
        private readonly \ArrayAccess $arrayAccessContainer = new PimpleContainer(),
    ) {
        self::$instance = $this;
    }

    public function get(string $id): mixed
    {
        return $this->arrayAccessContainer[$id];
    }

    public function has(string $id): bool
    {
        return isset($this->arrayAccessContainer[$id]);
    }

    public function set(string $id, mixed $item): void
    {
        $this->arrayAccessContainer[$id] = $item;
    }

    public function addClass(string $class): void
    {
        $this->arrayAccessContainer[$class] = fn() => new $class;
    }

    public function addRequestHanlder(string $class): void
    {
        $this->arrayAccessContainer[$class] = fn () => function (ServerRequestInterface $request) use ($class) {
            /** @var RequestHandlerInterface $instance */
            $instance = new $class();

            return $instance->handle($request);
        };
    }

    public function addMiddleware(string $class): void
    {
        $this->arrayAccessContainer[$class] = fn () => function (ServerRequestInterface $request, RequestHandlerInterface $handler) use ($class) {
            /** @var MiddlewareInterface $instance */
            $instance = new $class();

            return $instance->process($request, $handler);
        };
    }

    public function getRequestHandlerIndex(string $class): callable
    {
        return $this->get($class);
    }

    public static function getInstance(): self 
    {
        return self::$instance;
    }
}
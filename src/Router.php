<?php

namespace AlexWinter\Framework;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

final class Router 
{
    private array $routes = [];

    private function add(string $method, string $path, string $handler): void
    {
        $interfaces = class_implements($handler);

        if (!in_array(RequestHandlerInterface::class, $interfaces, true)) {
            throw new \InvalidArgumentException("$handler does not implement psr Psr\Http\Server\RequestHandlerInterface");
        }
        
        $this->routes[$method][$path] = $handler;
    }

    public function get(string $path, string $handler): void
    {
        $this->add('GET', $path, $handler);
    }

    public function post(string $path, string $handler): void
    {
        $this->add('POST', $path, $handler);
    }

    public function delete(string $path, string $handler): void
    {
        $this->add('DELETE', $path, $handler);
    }

    public function dispatch(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface 
    {
        $method = $request->getMethod();
        $uri = $request->getUri()->getPath();

        $handler = $this->routes[$method][$uri] ?? null;

        if (!$handler) {
            $response->withStatus(404);
        }

        return new $handler()->handle($request);
    }
}
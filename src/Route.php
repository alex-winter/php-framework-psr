<?php

namespace App;

use Slim\App;
use Slim\Interfaces\RouteInterface;

final class Route
{
    public function __construct(
        private readonly App $app,
    ) {
    }

    public function get(string $path, string $class): RouteInterface 
    {
        return $this->map('GET', $path, $class);
    }

    public function post(string $path, string $class): RouteInterface 
    {
        return $this->map('POST', $path, $class);
    }

    private function map(string $method, string $path, string $class): RouteInterface
    {
        return $this->app->map(
            [$method], 
            $path, 
            $this->app->getContainer()->get($class),
        );
    }
}
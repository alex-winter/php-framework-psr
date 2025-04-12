<?php

namespace App;

use Slim\App;

final class Route
{
    public function __construct(
        private readonly App $app,
    ) {
    }

    public function get(string $path, string $class): void 
    {
        $this->app->get($path, $this->app->getContainer()->get($class));
    }

    public function post(string $path, string $class): void 
    {
        $this->app->post($path, $this->app->getContainer()->get($class));
    }
}
<?php

namespace App;

use Pimple\Container as PimpleContainer;
use Psr\Container\ContainerInterface;

/**
 * @property IndexHandler $requestHandlerIndex
 */
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

    public function __get(string $id): mixed 
    {
        return $this->get($id);
    }

    public function __set(string $id, mixed $value): void 
    {
        $this->arrayAccessContainer[$id] = $value;
    }
}
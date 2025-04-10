<?php

namespace App;

use Pimple\Container as PimpleContainer;
use Pimple\Psr11\Container as PsrContainer;

/**
 * @property IndexHandler $requestHandlerIndex
 */
final class Container extends PsrContainer
{
    function __construct(
        private readonly PimpleContainer $pimple,
    ) {
    }

    public function __get(string $key): mixed
    {
        return $this->pimple[$key];
    }

    public function __set(string $key, mixed $value): void 
    {
        $this->pimple[$key] = $value;
    }
}
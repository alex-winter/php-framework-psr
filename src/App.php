<?php

namespace AlexWinter\Framework;

use AlexWinter\Framework\Router;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * @method self get(string $path, string $handler)
 */
final class App 
{
    function __construct(
        private readonly Router $router = new Router(),
    ) {
    }

    public static function make(
        Router $router = new Router(),
    ): self
    {
        return new self($router);
    }

    public function __call(string $name, array $arguments): mixed {
        if (method_exists($this->router, $name)) {
            return $this->router->$name(...$arguments);
        }

        throw new \BadMethodCallException('method does not exist');
    }

    public function run(ServerRequestInterface $request, ResponseInterface $response): void
    {      
        $response = $this->router->dispatch($request, $response);
     
        // emit response
        http_response_code($response->getStatusCode());
        
        foreach ($response->getHeaders() as $name => $values) {
            foreach ($values as $value) {
                header("$name: $value", false);
            }
        }
     
        echo $response->getBody();
    }
}
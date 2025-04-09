<?php

namespace AlexWinter\Framework;

use AlexWinter\Framework\Router;
use Laminas\Diactoros\ResponseFactory;
use Laminas\Diactoros\ServerRequestFactory;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestFactoryInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * @method self get(string $path, string $handler)
 * @method self post(string $path, string $handler)
 * @method self delete(string $path, string $handler)
 */
final class App 
{
    function __construct(
        private readonly ServerRequestFactoryInterface $requestFactory = new ServerRequestFactory(),
        private readonly ResponseFactoryInterface $responseFactory = new ResponseFactory(),
        private readonly Router $router = new Router(),
    ) {
    }

    public static function make(
        ServerRequestFactoryInterface $requestFactory = new ServerRequestFactory(),
        ResponseFactoryInterface $responseFactory = new ResponseFactory(),
        Router $router = new Router(),
    ): self
    {
        return new self(
            $requestFactory,
            $responseFactory,  
            $router,
        );
    }

    public function __call(string $name, array $arguments): mixed {
        if (method_exists($this->router, $name)) {
            return $this->router->$name(...$arguments);
        }

        throw new \BadMethodCallException('method does not exist');
    }

    public function run(
        ServerRequestInterface | null $request = null, 
        ResponseInterface | null $response = null,
    ): void
    {      
        $request ??= ServerRequestFactory::fromGlobals();
        $response ??= $this->responseFactory->createResponse();

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
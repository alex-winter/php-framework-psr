<?php

namespace AlexWinter\Framework;

use AlexWinter\Framework\Router;
use Laminas\Diactoros\ResponseFactory;
use Laminas\Diactoros\ServerRequestFactory;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestFactoryInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

/**
 * @method self get(string $path, string $handler)
 * @method self post(string $path, string $handler)
 * @method self delete(string $path, string $handler)
 */
final class App 
{
    /**
     * @var MiddlewareInterface[] $middleware
     */
    private array $middleware = [];

    function __construct(
        private readonly ServerRequestFactoryInterface $requestFactory = new ServerRequestFactory(),
        private readonly ResponseFactoryInterface $responseFactory = new ResponseFactory(),
        private readonly Router $router = new Router(),
    ) {
    }

    private function runMiddleware(ServerRequestInterface $request, ResponseInterface $response, int $index = 0): ResponseInterface
    {
        $middleware = $this->middleware[$index] ?? null;

        $next = fn (ServerRequestInterface $r) => $this->runMiddleware($r, $response, $index++);

        $nextHandler = new class ($next) implements RequestHandlerInterface {
            public function __construct(
                private $next,
            ) {
            }

            public function handle(ServerRequestInterface $request): ResponseInterface
            {
                $next = $this->next;

                return $next($request);
            }
        };

        $lastHandler = new class ($response) implements RequestHandlerInterface {
            public function __construct(
                private readonly ResponseInterface $response,
            ) {
            }

            public function handle(ServerRequestInterface $request): ResponseInterface
            {
                return $this->response;
            }
        };

        return $middleware->process(
            $request, 
            $index === count($this->middleware) -1 
             ? $lastHandler
             : $nextHandler
        );
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

    public function addMiddleware(MiddlewareInterface $middleware): void 
    {
        array_unshift($this->middleware, $middleware);
    }

    public function __call(string $name, array $arguments): mixed {
        if (method_exists($this->router, $name)) {
            $this->router->$name(...$arguments);

            return $this;
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

        $response = $this->runMiddleware($request, $response, 0);


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
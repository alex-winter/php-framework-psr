<?php

namespace App\RequestHandler;

use Laminas\Diactoros\Response\JsonResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

final class IndexRequestHandler implements RequestHandlerInterface
{
    public function __construct() 
    {
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        return new JsonResponse(
            data: ['hello' => [1, 2, 3]]
        );
    }
}
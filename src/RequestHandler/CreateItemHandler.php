<?php

namespace App;

use App\Service\ItemRepository;
use Laminas\Diactoros\Response\JsonResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

use function App\functions\get;

final class CreateItemHandler implements RequestHandlerInterface
{
    public function __construct(
        private readonly ItemRepository $itemRepository,
    ) {   
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        return new JsonResponse(
            data: ['hello' => [1, 2, 3]]
        );
    }
}
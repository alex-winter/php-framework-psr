<?php

namespace App\RequestHandler;

use App\Container;
use App\Service\ItemRepository;
use Laminas\Diactoros\Response\JsonResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

use function App\functions\get;

final class IndexRequestHandler implements RequestHandlerInterface
{
    private readonly ItemRepository $itemRepository;

    public function __construct() 
    {
        $this->itemRepository = get(ItemRepository::class);
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        return new JsonResponse(
            data: ['hello' => [1, 2, 3]]
        );
    }
}
<?php

namespace App\Middleware\Item;

use App\Dto\Item;
use App\Provider\ItemProvider;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

use function App\functions\get;

final class ParseRequestedData implements MiddlewareInterface
{
    private readonly ItemProvider $itemProvider;

    public function __construct()
    {
        $this->itemProvider = get(ItemProvider::class);
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $requestedData = $request->getParsedBody();

        $requestedName = (string)($requestedData['name'] ?? '');
        $requestedDueAt = (string)($requestedData['due_at'] ?? null);

        $dtoItem = new Item(
            trim($requestedName),
            $requestedDueAt,
        );

        $this->itemProvider->set($dtoItem);

        return $handler->handle($request);
    }
}
<?php

namespace App\RequestHandler;

use App\Entity\Item;
use App\Service\ItemMapToResponse;
use App\Service\ItemRepository;
use Laminas\Diactoros\Response\JsonResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

use function App\functions\get;

final class GetAllItemsHandler implements RequestHandlerInterface
{
    private readonly ItemRepository $itemRepository;
    private readonly ItemMapToResponse $itemMapToResponse;

    public function __construct() 
    {   
        $this->itemRepository = get(ItemRepository::class);
        $this->itemMapToResponse = get(ItemMapToResponse::class);
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $items = $this->itemRepository->getAll();

        $data = [
            'data' => array_map(
                fn (Item $item) => $this->itemMapToResponse->map($item), 
                $items,
            ),
        ];

        return new JsonResponse($data);
    }
}
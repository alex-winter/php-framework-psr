<?php

namespace App\RequestHandler;

use App\Entity\Item;
use App\Service\ItemRepository;
use DateTime;
use Laminas\Diactoros\Response\JsonResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

use function App\functions\get;

final class GetAllItemsHandler implements RequestHandlerInterface
{
    private readonly ItemRepository $itemRepository;

    public function __construct() 
    {   
        $this->itemRepository = get(ItemRepository::class);
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $items = $this->itemRepository->getAll();

        $data = [
            'items' => array_map(fn (Item $item) => [
                    
                'name' => $item->name,
                
                'created_at' => $item->createdAt->format(DateTime::ATOM),

                'due_at' => $item->dueAt?->format(DateTime::ATOM),

            ], $items),
        ];

        return new JsonResponse($data);
    }
}
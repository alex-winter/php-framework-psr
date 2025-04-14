<?php

namespace App\RequestHandler;

use App\Entity\Item;
use App\Provider\ItemProvider;
use App\Service\ItemMapToResponse;
use App\Service\ItemRepository;
use DateTimeImmutable;
use Laminas\Diactoros\Response\JsonResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

use function App\functions\get;

final class CreateItemHandler implements RequestHandlerInterface
{
    private readonly ItemRepository $itemRepository;
    private readonly ItemProvider $itemProvider;
    private readonly ItemMapToResponse $itemMapToResponse;

    public function __construct() 
    {
        $this->itemRepository = get(ItemRepository::class);
        $this->itemProvider = get(ItemProvider::class);
        $this->itemMapToResponse = get(ItemMapToResponse::class);
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $dto = $this->itemProvider->get();

        $this->itemRepository->persist(
            $item = new Item(
                name: $dto->name,
                dueAt: $dto->dueAt ? new DateTimeImmutable($dto->dueAt) : null,
            )
        );

        return new JsonResponse(
            data: [
                'data' => $this->itemMapToResponse->map($item)
            ],
            status: 201,
        );
    }
}
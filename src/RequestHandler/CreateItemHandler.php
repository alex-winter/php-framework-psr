<?php

namespace App\RequestHandler;

use App\Entity\Item;
use App\Provider\ItemProvider;
use App\Service\ItemRepository;
use DateTimeImmutable;
use Exception;
use Laminas\Diactoros\Response\JsonResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Slim\Exception\HttpBadRequestException;

use function App\functions\get;

final class CreateItemHandler implements RequestHandlerInterface
{
    private readonly ItemRepository $itemRepository;
    private readonly ItemProvider $itemProvider;

    public function __construct() 
    {
        $this->itemRepository = get(ItemRepository::class);
        $this->itemProvider = get(ItemProvider::class);
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $dto = $this->itemProvider->get();

        if ($dto->name === '') {
            throw new HttpBadRequestException($request, 'The request body must include a non-empty \'name\' string.');
        }

        if ($dto->dueAt && !$this->isValidDateTime($dto->dueAt)) {
            throw new HttpBadRequestException($request, 'due_at must be a valid datetime string');
        }

        $this->itemRepository->persist(
            $item = new Item(
                name: $dto->name,
                dueAt: $dto->dueAt ? new DateTimeImmutable($dto->dueAt) : null,
            )
        );

        return new JsonResponse(
            data: [
                'data' => [
                    'name' => $item->name,
                ]
            ],
            status: 201,
        );
    }

    private function isValidDateTime(string $value): bool
    {
        if (trim($value) === '') {
            return false;
        }

        try {
            new DateTimeImmutable($value);
            return true;
        } catch (Exception $e) {
            return false;
        }
    }
}
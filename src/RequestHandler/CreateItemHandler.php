<?php

namespace App\RequestHandler;

use App\Entity\Item;
use App\Service\ItemRepository;
use Laminas\Diactoros\Response\JsonResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Slim\Exception\HttpBadRequestException;

use function App\functions\get;

final class CreateItemHandler implements RequestHandlerInterface
{
    private readonly ItemRepository $itemRepository;

    public function __construct() 
    {
        $this->itemRepository = get(ItemRepository::class);   
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $requestedData = $request->getParsedBody();

        $requestedName = $requestedData['name'];

        var_dump($requestedName);

        if (!is_string($requestedName)) {
            throw new HttpBadRequestException($request, 'The request body must include a non-empty \'name\' string.');
        }

        $this->itemRepository->persist(
            new Item($requestedName)
        );

        return new JsonResponse([
            'success' => 'Successfully Created new Item'
        ]);
    }
}
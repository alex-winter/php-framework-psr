<?php

namespace App\Middleware\Item;

use App\Provider\ItemProvider;
use DateTimeImmutable;
use Exception;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Slim\Exception\HttpBadRequestException;

use function App\functions\get;

final class ValidateRequestedData implements MiddlewareInterface
{
    private readonly ItemProvider $itemProvider;

    public function __construct()
    {
        $this->itemProvider = get(ItemProvider::class);
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $dto = $this->itemProvider->get();

        if ($dto->name === '') {
            throw new HttpBadRequestException($request, 'The request body must include a non-empty \'name\' string.');
        }

        if ($dto->dueAt && !$this->isValidDateTime($dto->dueAt)) {
            throw new HttpBadRequestException($request, 'due_at must be a valid datetime string');
        }

        return $handler->handle($request);
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
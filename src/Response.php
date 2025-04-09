<?php

namespace AlexWinter\Framework;

use AlexWinter\Framework\Stream;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;

class Response implements ResponseInterface
{
    private StreamInterface $body;

    function __construct(
        private string $protocolVersion = '1.1',
        private array $headers = [],
        private int $statusCode = 200,
        private string $reasonPhrase = 'OK'
    ) 
    {
        $this->body = new Stream(fopen('php://memory', 'r+'));
    }

    public function getProtocolVersion(): string
    {
        return $this->protocolVersion;
    }

    public function withProtocolVersion($version): static
    {
        $clone = clone $this;
        $clone->protocolVersion = $version;
        return $clone;
    }

    public function getHeaders(): array
    {
        return $this->headers;
    }

    public function hasHeader($name): bool
    {
        return isset($this->headers[strtolower($name)]);
    }

    public function getHeader($name): array
    {
        $name = strtolower($name);
        return $this->headers[$name] ?? [];
    }

    public function getHeaderLine($name): string
    {
        return implode(', ', $this->getHeader($name));
    }

    public function withHeader($name, $value): static
    {
        $clone = clone $this;
        $clone->headers[strtolower($name)] = (array)$value;
        return $clone;
    }

    public function withAddedHeader($name, $value): static
    {
        $clone = clone $this;
        $lower = strtolower($name);
        $clone->headers[$lower] = array_merge($clone->headers[$lower] ?? [], (array)$value);
        return $clone;
    }

    public function withoutHeader($name): static
    {
        $clone = clone $this;
        unset($clone->headers[strtolower($name)]);
        return $clone;
    }

    public function getBody(): StreamInterface
    {
        return $this->body;
    }

    public function withBody(StreamInterface $body): static
    {
        $clone = clone $this;
        $clone->body = $body;
        return $clone;
    }

    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    public function withStatus($code, $reasonPhrase = ''): static
    {
        $clone = clone $this;
        $clone->statusCode = $code;
        $clone->reasonPhrase = $reasonPhrase !== '' ? $reasonPhrase : $this->getDefaultReasonPhrase($code);
        return $clone;
    }

    public function getReasonPhrase(): string
    {
        return $this->reasonPhrase;
    }

    private function normalizeHeaders(array $headers): array
    {
        $normalized = [];
        foreach ($headers as $name => $value) {
            $normalized[strtolower($name)] = (array)$value;
        }
        return $normalized;
    }

    private function getDefaultReasonPhrase(int $code): string
    {
        return self::PHRASES[$code] ?? '';
    }

    private const PHRASES = [
        200 => 'OK',
        201 => 'Created',
        204 => 'No Content',
        400 => 'Bad Request',
        401 => 'Unauthorized',
        403 => 'Forbidden',
        404 => 'Not Found',
        500 => 'Internal Server Error',
        502 => 'Bad Gateway',
        503 => 'Service Unavailable',
        // Add more if needed
    ];
}

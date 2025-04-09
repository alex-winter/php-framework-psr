<?php

namespace AlexWinter\Framework;

use AlexWinter\Framework\Uri;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\StreamInterface;
use Psr\Http\Message\UriInterface;

class ServerRequest implements ServerRequestInterface
{
    private string $method;
    private UriInterface $uri;
    private array $headers = [];
    private StreamInterface $body;
    private string $protocolVersion = '1.1';
    private string $requestTarget = '';
    private array $serverParams;
    private array $cookieParams = [];
    private array $queryParams = [];
    private array $uploadedFiles = [];
    private null|array|object $parsedBody = null;
    private array $attributes = [];

    public function __construct(
        string $method,
        UriInterface $uri,
        array $serverParams = [],
        ?StreamInterface $body = null,
        array $headers = []
    ) {
        $this->method = strtoupper($method);
        $this->uri = $uri;
        $this->serverParams = $serverParams;
        $this->body = $body ?? new Stream(fopen('php://memory', 'r+'));
        $this->headers = $this->normalizeHeaders($headers);
    }

    private function normalizeHeaders(array $headers): array
    {
        $normalized = [];
        foreach ($headers as $name => $value) {
            $normalized[strtolower($name)] = (array)$value;
        }
        return $normalized;
    }

    // ----- RequestInterface Methods -----

    public function getRequestTarget(): string
    {
        if ($this->requestTarget !== '') {
            return $this->requestTarget;
        }

        $path = $this->uri->getPath() ?: '/';
        $query = $this->uri->getQuery();
        return $query ? "$path?$query" : $path;
    }

    public function withRequestTarget($requestTarget): static
    {
        $clone = clone $this;
        $clone->requestTarget = $requestTarget;
        return $clone;
    }

    public function getMethod(): string
    {
        return $this->method;
    }

    public function withMethod($method): static
    {
        $clone = clone $this;
        $clone->method = strtoupper($method);
        return $clone;
    }

    public function getUri(): UriInterface
    {
        return $this->uri;
    }

    public function withUri(UriInterface $uri, $preserveHost = false): static
    {
        $clone = clone $this;
        $clone->uri = $uri;

        if (!$preserveHost) {
            $host = $uri->getHost();
            if ($host !== '') {
                $clone->headers['host'] = [$host];
            }
        }

        return $clone;
    }

    // ----- MessageInterface Methods -----

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
        return $this->headers[strtolower($name)] ?? [];
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
        $name = strtolower($name);
        $clone->headers[$name] = array_merge($clone->headers[$name] ?? [], (array)$value);
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

    // ----- ServerRequestInterface Methods -----

    public function getServerParams(): array
    {
        return $this->serverParams;
    }

    public function getCookieParams(): array
    {
        return $this->cookieParams;
    }

    public function withCookieParams(array $cookies): static
    {
        $clone = clone $this;
        $clone->cookieParams = $cookies;
        return $clone;
    }

    public function getQueryParams(): array
    {
        return $this->queryParams;
    }

    public function withQueryParams(array $query): static
    {
        $clone = clone $this;
        $clone->queryParams = $query;
        return $clone;
    }

    public function getUploadedFiles(): array
    {
        return $this->uploadedFiles;
    }

    public function withUploadedFiles(array $uploadedFiles): static
    {
        $clone = clone $this;
        $clone->uploadedFiles = $uploadedFiles;
        return $clone;
    }

    public function getParsedBody(): null|array|object
    {
        return $this->parsedBody;
    }

    public function withParsedBody($data): static
    {
        $clone = clone $this;
        $clone->parsedBody = $data;
        return $clone;
    }

    public function getAttributes(): array
    {
        return $this->attributes;
    }

    public function getAttribute($name, $default = null): mixed
    {
        return $this->attributes[$name] ?? $default;
    }

    public function withAttribute($name, $value): static
    {
        $clone = clone $this;
        $clone->attributes[$name] = $value;
        return $clone;
    }

    public function withoutAttribute($name): static
    {
        $clone = clone $this;
        unset($clone->attributes[$name]);
        return $clone;
    }

    public static function fromGlobals(): static
    {
        $method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
        $uri = new Uri(
            scheme: (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http',
            host: $_SERVER['HTTP_HOST'] ?? 'localhost',
            path: parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) ?? '/',
            query: $_SERVER['QUERY_STRING'] ?? ''
        );

        $headers = function_exists('getallheaders') ? getallheaders() : self::parseHeaders($_SERVER);
        $body = new Stream(fopen('php://input', 'r'));

        $instance = new static(
            method: $method,
            uri: $uri,
            serverParams: $_SERVER,
            body: $body,
            headers: $headers
        );

        return $instance
            ->withQueryParams($_GET)
            ->withCookieParams($_COOKIE)
            ->withParsedBody($_POST)
            ->withUploadedFiles(self::normalizeFiles($_FILES));
    }

    private static function parseHeaders(array $server): array
    {
        $headers = [];
        foreach ($server as $key => $value) {
            if (str_starts_with($key, 'HTTP_')) {
                // Convert HTTP_HEADER_NAME to Header-Name
                $name = str_replace('_', '-', strtolower(substr($key, 5)));
                $name = ucwords($name, '-');
                $headers[$name] = $value;
            } elseif (in_array($key, ['CONTENT_TYPE', 'CONTENT_LENGTH'])) {
                $name = str_replace('_', '-', strtolower($key));
                $name = ucwords($name, '-');
                $headers[$name] = $value;
            }
        }
        return $headers;
    }

    private static function normalizeFiles(array $files): array
    {
        $normalized = [];

        foreach ($files as $field => $file) {
            if (is_array($file['name'])) {
                // Handle nested file fields
                $normalized[$field] = [];
                foreach ($file['name'] as $index => $name) {
                    $normalized[$field][] = new UploadedFile(
                        $file['tmp_name'][$index],
                        $file['size'][$index],
                        $file['error'][$index],
                        $name,
                        $file['type'][$index]
                    );
                }
            } else {
                // Single file upload
                $normalized[$field] = new UploadedFile(
                    $file['tmp_name'],
                    $file['size'],
                    $file['error'],
                    $file['name'],
                    $file['type']
                );
            }
        }

        return $normalized;
    }
}

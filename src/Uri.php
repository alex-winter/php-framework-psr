<?php

namespace AlexWinter\Framework;

use Psr\Http\Message\UriInterface;

class Uri implements UriInterface
{
    private string $scheme = '';
    private string $userInfo = '';
    private string $host = '';
    private int|null $port = null;
    private string $path = '';
    private string $query = '';
    private string $fragment = '';

    public function __construct(
        string $scheme = '',
        string $host = '',
        string $path = '',
        string $query = '',
        string $fragment = '',
        int|null $port = null,
        string $userInfo = ''
    ) {
        $this->scheme = strtolower($scheme);
        $this->host = strtolower($host);
        $this->path = $path;
        $this->query = $query;
        $this->fragment = $fragment;
        $this->port = $port;
        $this->userInfo = $userInfo;
    }

    public function __toString(): string
    {
        $uri = '';

        if ($this->scheme !== '') {
            $uri .= $this->scheme . ':';
        }

        if ($this->host !== '') {
            $uri .= '//';

            if ($this->userInfo !== '') {
                $uri .= $this->userInfo . '@';
            }

            $uri .= $this->host;

            if ($this->port !== null) {
                $uri .= ':' . $this->port;
            }
        }

        $uri .= $this->path;

        if ($this->query !== '') {
            $uri .= '?' . $this->query;
        }

        if ($this->fragment !== '') {
            $uri .= '#' . $this->fragment;
        }

        return $uri;
    }

    public function getScheme(): string
    {
        return $this->scheme;
    }

    public function getAuthority(): string
    {
        if ($this->host === '') {
            return '';
        }

        $authority = '';

        if ($this->userInfo !== '') {
            $authority .= $this->userInfo . '@';
        }

        $authority .= $this->host;

        if ($this->port !== null && $this->port !== $this->getDefaultPortForScheme($this->scheme)) {
            $authority .= ':' . $this->port;
        }

        return $authority;
    }

    public function getUserInfo(): string
    {
        return $this->userInfo;
    }

    public function getHost(): string
    {
        return $this->host;
    }

    public function getPort(): ?int
    {
        return $this->port;
    }

    public function getPath(): string
    {
        return $this->path;
    }

    public function getQuery(): string
    {
        return $this->query;
    }

    public function getFragment(): string
    {
        return $this->fragment;
    }

    public function withScheme($scheme): static
    {
        $clone = clone $this;
        $clone->scheme = strtolower($scheme);
        return $clone;
    }

    public function withUserInfo($user, $password = null): static
    {
        $clone = clone $this;
        $clone->userInfo = $password !== null ? "$user:$password" : $user;
        return $clone;
    }

    public function withHost($host): static
    {
        $clone = clone $this;
        $clone->host = strtolower($host);
        return $clone;
    }

    public function withPort($port): static
    {
        $clone = clone $this;
        $clone->port = $port;
        return $clone;
    }

    public function withPath($path): static
    {
        $clone = clone $this;
        $clone->path = $path;
        return $clone;
    }

    public function withQuery($query): static
    {
        $clone = clone $this;
        $clone->query = $query;
        return $clone;
    }

    public function withFragment($fragment): static
    {
        $clone = clone $this;
        $clone->fragment = $fragment;
        return $clone;
    }

    private function getDefaultPortForScheme(string $scheme): ?int
    {
        return match ($scheme) {
            'http' => 80,
            'https' => 443,
            default => null,
        };
    }
}

<?php

namespace AlexWinter\Framework;

use Psr\Http\Message\StreamInterface;
use RuntimeException;

class Stream implements StreamInterface
{
    private $stream;
    private ?string $uri;
    private bool $readable;
    private bool $writable;
    private bool $seekable;
    private ?int $size;

    public function __construct($stream)
    {
        if (!is_resource($stream)) {
            throw new \InvalidArgumentException('Stream must be a valid resource.');
        }

        $this->stream = $stream;
        $meta = stream_get_meta_data($stream);
        $this->uri = $meta['uri'] ?? null;
        $this->seekable = $meta['seekable'];
        $this->readable = strpbrk($meta['mode'], 'r+') !== false;
        $this->writable = strpbrk($meta['mode'], 'waxc+') !== false;
        $this->size = null;
    }

    public function __toString(): string
    {
        try {
            if ($this->isSeekable()) {
                $this->rewind();
            }

            return $this->getContents();
        } catch (\Throwable $e) {
            return '';
        }
    }

    public function close(): void
    {
        if (is_resource($this->stream)) {
            fclose($this->stream);
        }

        $this->detach();
    }

    public function detach()
    {
        $result = $this->stream;
        $this->stream = null;
        $this->uri = null;
        $this->readable = false;
        $this->writable = false;
        $this->seekable = false;
        $this->size = null;

        return $result;
    }

    public function getSize(): ?int
    {
        if ($this->size !== null) {
            return $this->size;
        }

        if (!is_resource($this->stream)) {
            return null;
        }

        $stats = fstat($this->stream);
        return $stats ? $stats['size'] : null;
    }

    public function tell(): int
    {
        $result = ftell($this->stream);

        if ($result === false) {
            throw new RuntimeException('Unable to determine stream position.');
        }

        return $result;
    }

    public function eof(): bool
    {
        return !$this->stream || feof($this->stream);
    }

    public function isSeekable(): bool
    {
        return $this->seekable;
    }

    public function seek($offset, $whence = SEEK_SET): void
    {
        if (!$this->isSeekable()) {
            throw new RuntimeException('Stream is not seekable.');
        }

        if (fseek($this->stream, $offset, $whence) === -1) {
            throw new RuntimeException('Unable to seek in stream.');
        }
    }

    public function rewind(): void
    {
        $this->seek(0);
    }

    public function isWritable(): bool
    {
        return $this->writable;
    }

    public function write($string): int
    {
        if (!$this->isWritable()) {
            throw new RuntimeException('Stream is not writable.');
        }

        $result = fwrite($this->stream, $string);

        if ($result === false) {
            throw new RuntimeException('Unable to write to stream.');
        }

        $this->size = null; // Invalidate cached size

        return $result;
    }

    public function isReadable(): bool
    {
        return $this->readable;
    }

    public function read($length): string
    {
        if (!$this->isReadable()) {
            throw new RuntimeException('Stream is not readable.');
        }

        $result = fread($this->stream, $length);

        if ($result === false) {
            throw new RuntimeException('Unable to read from stream.');
        }

        return $result;
    }

    public function getContents(): string
    {
        if (!$this->isReadable()) {
            throw new RuntimeException('Stream is not readable.');
        }

        $contents = stream_get_contents($this->stream);

        if ($contents === false) {
            throw new RuntimeException('Unable to get stream contents.');
        }

        return $contents;
    }

    public function getMetadata($key = null): mixed
    {
        if (!is_resource($this->stream)) {
            return $key === null ? [] : null;
        }

        $meta = stream_get_meta_data($this->stream);

        return $key === null ? $meta : ($meta[$key] ?? null);
    }
}

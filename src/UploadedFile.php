<?php

namespace AlexWinter\Framework;

use Psr\Http\Message\UploadedFileInterface;
use Psr\Http\Message\StreamInterface;
use RuntimeException;

class UploadedFile implements UploadedFileInterface
{
    private StreamInterface $stream;
    private ?int $size;
    private int $error;
    private string $clientFilename;
    private string $clientMediaType;

    public function __construct(
        string $tmpName,
        int $size,
        int $error,
        string $clientFilename = '',
        string $clientMediaType = ''
    ) {
        // Create a stream for the uploaded file
        $this->stream = new Stream(fopen($tmpName, 'r'));
        $this->size = $size;
        $this->error = $error;
        $this->clientFilename = $clientFilename;
        $this->clientMediaType = $clientMediaType;
    }

    public function getStream(): StreamInterface
    {
        return $this->stream;
    }

    public function getSize(): ?int
    {
        return $this->size;
    }

    public function getError(): int
    {
        return $this->error;
    }

    public function getClientFilename(): string
    {
        return $this->clientFilename;
    }

    public function getClientMediaType(): string
    {
        return $this->clientMediaType;
    }

    public function moveTo($targetPath): void
    {
        if ($this->error !== UPLOAD_ERR_OK) {
            throw new RuntimeException('Cannot move uploaded file: ' . $this->error);
        }

        if (!is_writable(dirname($targetPath))) {
            throw new RuntimeException('The target directory is not writable.');
        }

        if (!rename($this->stream->getMetadata('uri'), $targetPath)) {
            throw new RuntimeException('Failed to move uploaded file.');
        }
    }
}

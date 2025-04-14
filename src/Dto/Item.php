<?php

namespace App\Dto;

final class Item 
{
    public function __construct(
        public readonly string $name,
        public readonly ?string $dueAt,
    ) {}
}
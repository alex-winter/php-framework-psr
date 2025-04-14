<?php

namespace App\Provider;

use App\Dto\Item;

final class ItemProvider 
{
    private ?Item $item = null;

    public function get(): Item 
    {
        return $this->item;
    }

    public function set(Item $item): void
    {
        if ($this->item !== null) {
            throw new \LogicException('Item has already been set and cannot be modified.');
        }

        $this->item = $item;
    }
}
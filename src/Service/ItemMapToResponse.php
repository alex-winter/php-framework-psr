<?php

namespace App\Service;

use App\Entity\Item;
use DateTime;

final class ItemMapToResponse
{
    public function map(Item $item): array 
    {
        return [
                    
            'name' => $item->name,
            
            'created_at' => $item->createdAt->format(DateTime::ATOM),

            'due_at' => $item->dueAt?->format(DateTime::ATOM),

        ];
    }
}
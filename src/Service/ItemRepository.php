<?php

namespace App\Service;

use App\Entity\Item;
use Doctrine\ORM\EntityManager;

use function App\functions\get;

final class ItemRepository
{
    private readonly EntityManager $entityManager;

    public function __construct()
    {
        $this->entityManager = get('entity-manager');
    }

    public function persist(Item $item): void
    {
        $this->entityManager->persist($item);
        $this->entityManager->flush();
    }
}
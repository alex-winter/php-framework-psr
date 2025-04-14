<?php

namespace App\Service;

use App\Entity\Item;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;

use function App\functions\get;

final class ItemRepository
{
    private readonly EntityManager $entityManager;
    private readonly EntityRepository $entityRepository;

    public function __construct()
    {
        $this->entityManager = get('entity-manager');
        $this->entityRepository = $this->entityManager->getRepository(Item::class);
    }

    public function persist(Item $item): void
    {
        $this->entityManager->persist($item);
        $this->entityManager->flush();
    }

    public function getAll(): array
    {
        return $this->entityRepository->findAll();
    }
}
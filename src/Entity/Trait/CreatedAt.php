<?php

namespace App\Entity\Trait;

use Doctrine\ORM\Mapping as ORM;

trait CreatedAt
{
    #[ORM\Column(type: 'datetime_immutable')]
    public \DateTimeImmutable $createdAt {
        get => $this->createdAt;
    }

    #[ORM\PrePersist]
    public function setCreatedAtValue(): void
    {
        $this->createdAt = new \DateTimeImmutable();
    }
}
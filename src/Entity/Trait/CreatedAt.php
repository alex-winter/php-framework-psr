<?php

namespace App\Entity\Trait;

use Doctrine\ORM\Mapping as ORM;

trait CreatedAt
{
    #[ORM\Column(type: 'datetime_immutable')]
    private \DateTimeImmutable $createdAt;

    public function getCreatedAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }

    #[ORM\PrePersist]
    public function setCreatedAtValue(): void
    {
        if (!isset($this->createdAt)) {
            $this->createdAt = new \DateTimeImmutable();
        }
    }

    public function getCreatedAtAsAtom(): string 
    {
        return $this->createdAt->format(\DateTime::ATOM);
    }
}
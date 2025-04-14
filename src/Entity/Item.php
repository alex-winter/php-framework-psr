<?php

namespace App\Entity;

use App\Entity\Trait\CreatedAt;
use App\Entity\Trait\Id;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'items')]
class Item
{
    use Id;
    use CreatedAt;

    #[ORM\Column(type: 'string')]
    public string $name {
        get => $this->name;
    }

    #[ORM\Column(type: 'datetime_immutable', nullable: true)]
    public ?\DateTimeImmutable $dueAt {
        get => $this->dueAt;
    }
}

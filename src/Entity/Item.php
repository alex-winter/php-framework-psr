<?php

namespace App\Entity;

use App\Entity\Trait\CreatedAt;
use App\Entity\Trait\Id;
use Doctrine\ORM\Mapping as ORM;
use JsonSerializable;

#[ORM\Entity]
#[ORM\Table(name: 'items')]
class Item implements JsonSerializable
{
    use Id;
    use CreatedAt;

    #[ORM\Column(type: 'string')]
    private string $name;

    public function jsonSerialize(): array {
        return [
            'name' => $this->name,
            'created_at' => $this->getCreatedAt()->format(\DateTime::ATOM),
        ];
    }
}

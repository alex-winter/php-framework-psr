<?php

namespace App\Entity;

use App\Entity\Trait\Id;
use Doctrine\ORM\Mapping as ORM;
use JsonSerializable;

#[ORM\Entity]
#[ORM\Table(name: 'items')]
class Item implements JsonSerializable
{
    use Id;

    #[ORM\Column(type: 'string')]
    private string $name;

    public function jsonSerialize(): array {
        return [
            'name' => $this->name,
        ];
    }
}

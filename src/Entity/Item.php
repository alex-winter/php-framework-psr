<?php

namespace App\Entity;

use App\Entity\Trait\Id;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'items')]
class Item
{
    use Id;

    #[ORM\Column(type: 'string')]
    private string $name;
}

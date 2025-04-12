<?php

namespace App\Entity\Trait;

use Doctrine\ORM\Mapping as ORM;

trait Id 
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private int $id;
}
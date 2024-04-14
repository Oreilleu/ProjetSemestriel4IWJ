<?php

namespace App\Entity;

use App\Repository\RapportFinanciersRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RapportFinanciersRepository::class)]
class RapportFinanciers
{

    #[ORM\Id]
    #[ORM\ManyToOne(inversedBy: 'rapportFinanciers')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Entreprises $id = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?Entreprises $id): static
    {
        $this->id = $id;

        return $this;
    }
}

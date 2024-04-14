<?php

namespace App\Entity;

use App\Repository\InterractionsRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: InterractionsRepository::class)]
class Interractions
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'interractions')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Devis $id_devis = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIdDevis(): ?Devis
    {
        return $this->id_devis;
    }

    public function setIdDevis(?Devis $id_devis): static
    {
        $this->id_devis = $id_devis;

        return $this;
    }
}

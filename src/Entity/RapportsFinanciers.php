<?php

namespace App\Entity;

use App\Repository\RapportsFinanciersRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RapportsFinanciersRepository::class)]
class RapportsFinanciers
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?float $montant_total = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $created_at = null;

    #[ORM\ManyToOne(inversedBy: 'rapportsFinanciers')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Entreprises $id_entreprise = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): static
    {
        $this->id = $id;

        return $this;
    }

    public function getMontantTotal(): ?float
    {
        return $this->montant_total;
    }

    public function setMontantTotal(float $montant_total): static
    {
        $this->montant_total = $montant_total;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeImmutable $created_at): static
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function getIdEntreprise(): ?Entreprises
    {
        return $this->id_entreprise;
    }

    public function setIdEntreprise(?Entreprises $id_entreprise): static
    {
        $this->id_entreprise = $id_entreprise;

        return $this;
    }
}

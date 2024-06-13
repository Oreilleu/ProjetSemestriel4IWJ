<?php

namespace App\Entity;

use App\Repository\PaiementsRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PaiementsRepository::class)]
class Paiements
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?float $montant = null;

    #[ORM\Column]
    private ?string $method = null;

    #[ORM\ManyToOne(inversedBy: 'paiements')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Factures $id_facture = null;

    #[ORM\Column(options:['default' => 'CURRENT_TIMESTAMP'])]
    private ?\DateTimeImmutable $created_at = null;
    
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIdFacture(): ?Factures
    {
        return $this->id_facture;
    }

    public function setIdFacture(?Factures $id_facture): static
    {
        $this->id_facture = $id_facture;

        return $this;
    }

    public function getMethod(): ?string
    {
        return $this->method;
    }

    public function setMethod(string $method): static
    {
        $this->method = $method;

        return $this;
    }

    public function getMontant(): ?float
    {
        return $this->montant;
    }

    public function setMontant(float $montant): static
    {
        $this->montant = $montant;

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
}

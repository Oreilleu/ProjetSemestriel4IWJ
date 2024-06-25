<?php

namespace App\Entity;

use App\Repository\LigneFactureRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: LigneFactureRepository::class)]
class LigneFacture
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?string $id = null;

    #[ORM\Column(nullable: false)]
    private ?\DateTimeImmutable $created_at_facture = null;

    #[ORM\Column(nullable: false)]
    private ?string $id_str_factures = null;

    #[ORM\Column(nullable: false)]
    private ?string $firstname_client = null;

    #[ORM\Column(nullable: false)]
    private ?string $lastname_client = null;

    #[ORM\Column(nullable: false)]
    private ?string $taxe = null;

    #[ORM\Column(nullable: false)]
    private ?float $total_ht = null;
    
    #[ORM\Column(nullable: false)]
    private ?float $total_ttc = null;

    #[ORM\ManyToOne(targetEntity: RapportsFinanciers::class, inversedBy: 'lignesFactures')]
    #[ORM\JoinColumn(nullable: true)]
    private ?RapportsFinanciers $id_rapport_financier = null;

    public function getIdRapportFinancier(): ?RapportsFinanciers
    {
        return $this->id_rapport_financier;
    }

    public function setIdRapportFinancier(?RapportsFinanciers $id_rapport_financier): static
    {
        $this->id_rapport_financier = $id_rapport_financier;

        return $this;
    }

    public function getCreatedAtFacture(): ?\DateTimeImmutable
    {
        return $this->created_at_facture;
    }
    
    public function setCreatedAtFacture(\DateTimeImmutable $created_at_facture): static
    {
        $this->created_at_facture = $created_at_facture;

        return $this;
    }

    public function getIdStrFactures(): ?string
    {
        return $this->id_str_factures;
    }

    public function setIdStrFactures(string $id_str_factures): static
    {
        $this->id_str_factures = $id_str_factures;

        return $this;
    }

    public function getFirstnameClient(): ?string
    {
        return $this->firstname_client;
    }

    public function setFirstnameClient(string $firstname_client): static
    {
        $this->firstname_client = $firstname_client;

        return $this;
    }

    public function getLastnameClient(): ?string
    {
        return $this->lastname_client;
    }

    public function setLastnameClient(string $lastname_client): static
    {
        $this->lastname_client = $lastname_client;

        return $this;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): static
    {
        $this->id = $id;

        return $this;
    }

    public function getTotalHt(): ?float
    {
        return $this->total_ht;
    }

    public function setTotalHt(?float $total_ht): static
    {
        $this->total_ht = $total_ht;

        return $this;
    }

    public function getTotalTtc(): ?float
    {
        return $this->total_ttc;
    }

    public function setTotalTtc(?float $total_ttc): static
    {
        $this->total_ttc = $total_ttc;

        return $this;
    }

    public function getTaxe(): ?float
    {
        return $this->taxe;
    }

    public function setTaxe(?float $taxe): static
    {
        $this->taxe = $taxe;

        return $this;
    }
}

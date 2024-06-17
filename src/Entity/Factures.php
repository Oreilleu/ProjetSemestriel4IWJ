<?php

namespace App\Entity;

use App\Repository\FacturesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: FacturesRepository::class)]
class Factures
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?string $statut = null;
    
    #[ORM\Column]
    private ?float $taxe = null;

    
    #[ORM\Column(nullable: false)]
    private ?float $total_ht = null;
    
    #[ORM\Column(nullable: false)]
    private ?float $total_ttc = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $last_relance = null;
    
    #[ORM\Column(options:['default' => 'CURRENT_TIMESTAMP'])]
    private ?\DateTimeImmutable $created_at = null;
    
    #[ORM\ManyToOne(inversedBy: 'factures')]
    #[ORM\JoinColumn(nullable: true, onDelete: 'SET NULL')]
    private ?Devis $id_devis = null;
    
    #[ORM\ManyToOne(targetEntity: Entreprises::class, inversedBy: 'factures')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Entreprises $id_entreprise = null;
    
    #[ORM\OneToMany(mappedBy: 'id_factures', targetEntity: LignesDevis::class, cascade: ["remove"])]
    private Collection $lignesDevis;
    
    #[ORM\OneToMany(mappedBy: 'id_factures', targetEntity: Interractions::class)]
    private Collection $interractions;
    
    #[ORM\OneToMany(mappedBy: 'id_facture', targetEntity: Paiements::class, cascade: ["remove"])]
    #[ORM\JoinColumn(nullable: true)]
    private Collection $paiements;
    
    #[ORM\ManyToOne(targetEntity: Clients::class, inversedBy: 'facture')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Clients $id_client = null;

    public function __construct()
    {
        $this->paiements = new ArrayCollection();
        $this->lignesDevis = new ArrayCollection();
        $this->interractions = new ArrayCollection();
    }

    public function getIdEntreprise(): ?Entreprises
    {
        return $this->id_entreprise;
    }

    public function setIdEntreprise(?Entreprises $id_entreprise): self
    {
        $this->id_entreprise = $id_entreprise;

        return $this;
    }

    public function getClient(): ?Clients
    {
        return $this->id_client;
    }

    public function setClient(?Clients $id_client): self
    {
        $this->id_client = $id_client;

        return $this;
    }

    public function getLastRelance(): ?\DateTimeImmutable
    {
        return $this->last_relance;
    }

    public function setLastRelance(?\DateTimeImmutable $last_relance): self
    {
        $this->last_relance = $last_relance;

        return $this;
    }

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

    public function getStatut(): ?string
    {
        return $this->statut;
    }

    public function setStatut(string $statut): static
    {
        $this->statut = $statut;

        return $this;
    }

    public function getTaxe(): ?float
    {
        return $this->taxe;
    }

    public function setTaxe(float $taxe): static
    {
        $this->taxe = $taxe;

        return $this;
    }

    // public function getNameClient(): ?string
    // {
    //     return $this->name_client;
    // }

    // public function setNameClient(string $name_client): static
    // {
    //     $this->name_client = $name_client;

    //     return $this;
    // }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeImmutable $created_at): static
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function getPaiements(): Collection
    {
        return $this->paiements;
    }

    public function addPaiement(Paiements $paiement): static
    {
        if (!$this->paiements->contains($paiement)) {
            $this->paiements->add($paiement);
            $paiement->setIdFacture($this);
        }

        return $this;
    }

    public function removePaiement(Paiements $paiement): static
    {
        if ($this->paiements->removeElement($paiement)) {
            if ($paiement->getIdFacture() === $this) {
                $paiement->setIdFacture(null);
            }
        }

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

    public function getLignesDevis(): Collection
    {
        return $this->lignesDevis;
    }

    public function addLignesDevi(LignesDevis $lignesDevi): static
    {
        if (!$this->lignesDevis->contains($lignesDevi)) {
            $this->lignesDevis->add($lignesDevi);
            $lignesDevi->setIdFactures($this);
        }

        return $this;
    }

    public function removeLignesDevi(LignesDevis $lignesDevi): static
    {
        if ($this->lignesDevis->removeElement($lignesDevi)) {
            if ($lignesDevi->getIdFactures() === $this) {
                $lignesDevi->setIdFactures(null);
            }
        }

        return $this;
    }

    public function getInterractions(): Collection
    {
        return $this->interractions;
    }

    public function addInterraction(Interractions $interraction): static
    {
        if (!$this->interractions->contains($interraction)) {
            $this->interractions->add($interraction);
            $interraction->setIdFactures($this);
        }

        return $this;
    }

    public function removeInterraction(Interractions $interraction): static
    {
        if ($this->interractions->removeElement($interraction)) {
            if ($interraction->getIdFactures() === $this) {
                $interraction->setIdFactures(null);
            }
        }

        return $this;
    }
}

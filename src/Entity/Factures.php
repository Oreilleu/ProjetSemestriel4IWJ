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

    #[ORM\Column]
    private ?string $name_client = null;

    #[ORM\Column(options:['default' => 'CURRENT_TIMESTAMP'])]
    private ?\DateTimeImmutable $created_at = null;

    #[ORM\ManyToOne(inversedBy: 'factures')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Devis $id_devis = null;

    #[ORM\OneToMany(mappedBy: 'id_facture', targetEntity: Paiements::class, orphanRemoval: true)]
    #[ORM\JoinColumn(nullable: true)]
    private Collection $paiements;

    #[ORM\OneToMany(mappedBy: 'id_facture', targetEntity: ModePaiements::class, orphanRemoval: true)]
    #[ORM\JoinColumn(nullable: true)]
    private Collection $modePaiements;

    #[ORM\OneToMany(mappedBy: 'id_facture', targetEntity: LignesDevis::class)]
    private Collection $lignesDevis;

    #[ORM\Column(nullable: false)]
    private ?float $total_ht = null;

    #[ORM\Column(nullable: false)]
    private ?float $total_ttc = null;

    public function __construct()
    {
        $this->paiements = new ArrayCollection();
        $this->modePaiements = new ArrayCollection();
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

    public function getNameClient(): ?string
    {
        return $this->name_client;
    }

    public function setNameClient(string $name_client): static
    {
        $this->name_client = $name_client;

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

    /**
     * @return Collection<int, Paiements>
     */
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
            // set the owning side to null (unless already changed)
            if ($paiement->getIdFacture() === $this) {
                $paiement->setIdFacture(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, ModePaiements>
     */
    public function getModePaiements(): Collection
    {
        return $this->modePaiements;
    }

    public function addModePaiement(ModePaiements $modePaiement): static
    {
        if (!$this->modePaiements->contains($modePaiement)) {
            $this->modePaiements->add($modePaiement);
            $modePaiement->setIdFacture($this);
        }

        return $this;
    }

    public function removeModePaiement(ModePaiements $modePaiement): static
    {
        if ($this->modePaiements->removeElement($modePaiement)) {
            // set the owning side to null (unless already changed)
            if ($modePaiement->getIdFacture() === $this) {
                $modePaiement->setIdFacture(null);
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
            // set the owning side to null (unless already changed)
            if ($lignesDevi->getIdFactures() === $this) {
                $lignesDevi->setIdFactures(null);
            }
        }

        return $this;
    }
}

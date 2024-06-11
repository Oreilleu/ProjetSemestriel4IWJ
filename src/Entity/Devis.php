<?php

namespace App\Entity;

use App\Repository\DevisRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DevisRepository::class)]
class Devis
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $date = null;

    #[ORM\Column(length: 255)]
    private ?string $description = null;

    #[ORM\Column]
    private ?string $statut = null;

    #[ORM\Column]
    private ?float $taxe = null;

    #[ORM\OneToMany(mappedBy: 'id_devis', targetEntity: Factures::class, orphanRemoval: true)]
    private Collection $factures;

    #[ORM\OneToMany(mappedBy: 'id_devis', targetEntity: Relances::class, orphanRemoval: true)]
    private Collection $relances;

    #[ORM\OneToMany(mappedBy: 'id_devis', targetEntity: Interractions::class, orphanRemoval: true)]
    private Collection $interractions;

    #[ORM\Column(nullable: false)]
    private ?float $total_ht = null;

    #[ORM\ManyToOne(targetEntity: Entreprises::class, inversedBy: 'devis')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Entreprises $id_entreprise = null;

    #[ORM\ManyToOne(targetEntity: Lots::class, inversedBy: 'devis')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Lots $id_lots = null;

    #[ORM\ManyToOne(targetEntity: Clients::class, inversedBy: 'devis')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Clients $client;

    #[ORM\OneToMany(mappedBy: 'id_devis', targetEntity: LignesDevis::class, cascade: ["remove"])]
    private Collection $lignesDevis;

    public function __construct()
    {
        $this->factures = new ArrayCollection();
        $this->relances = new ArrayCollection();
        $this->interractions = new ArrayCollection();
        $this->lignesDevis = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): static
    {
        $this->date = $date;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

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

    /**
     * @return Collection<int, Factures>
     */
    public function getFactures(): Collection
    {
        return $this->factures;
    }

    public function addFacture(Factures $facture): static
    {
        if (!$this->factures->contains($facture)) {
            $this->factures->add($facture);
            $facture->setIdDevis($this);
        }

        return $this;
    }

    public function removeFacture(Factures $facture): static
    {
        if ($this->factures->removeElement($facture)) {
            // set the owning side to null (unless already changed)
            if ($facture->getIdDevis() === $this) {
                $facture->setIdDevis(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Relances>
     */
    public function getRelances(): Collection
    {
        return $this->relances;
    }

    public function addRelance(Relances $relance): static
    {
        if (!$this->relances->contains($relance)) {
            $this->relances->add($relance);
            $relance->setIdDevis($this);
        }

        return $this;
    }

    public function removeRelance(Relances $relance): static
    {
        if ($this->relances->removeElement($relance)) {
            // set the owning side to null (unless already changed)
            if ($relance->getIdDevis() === $this) {
                $relance->setIdDevis(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Interractions>
     */
    public function getInterractions(): Collection
    {
        return $this->interractions;
    }

    public function addInterraction(Interractions $interraction): static
    {
        if (!$this->interractions->contains($interraction)) {
            $this->interractions->add($interraction);
            $interraction->setIdDevis($this);
        }

        return $this;
    }

    public function removeInterraction(Interractions $interraction): static
    {
        if ($this->interractions->removeElement($interraction)) {
            // set the owning side to null (unless already changed)
            if ($interraction->getIdDevis() === $this) {
                $interraction->setIdDevis(null);
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

    public function getIdLots(): ?lots
    {
        return $this->id_lots;
    }

    public function setIdLots(?lots $id_lots): static
    {
        $this->id_lots = $id_lots;

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
            $lignesDevi->setIdDevis($this);
        }

        return $this;
    }

    public function removeLignesDevi(LignesDevis $lignesDevi): static
    {
        if ($this->lignesDevis->removeElement($lignesDevi)) {
            // set the owning side to null (unless already changed)
            if ($lignesDevi->getIdDevis() === $this) {
                $lignesDevi->setIdDevis(null);
            }
        }

        return $this;
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
        return $this->client;
    }

    public function setClient(?Clients $client): self
    {
        $this->client = $client;

        return $this;
    }
}
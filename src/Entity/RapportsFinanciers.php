<?php

namespace App\Entity;

use App\Repository\RapportsFinanciersRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RapportsFinanciersRepository::class)]
class RapportsFinanciers
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?float $total_ht = null;

    #[ORM\Column]
    private ?float $total_ttc = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $created_at = null;

    #[ORM\Column(nullable: false)]
    private ?\DateTimeImmutable $start_date = null;

    #[ORM\Column(nullable: false)]
    private ?\DateTimeImmutable $end_date = null;

    #[ORM\OneToMany(mappedBy: 'id_rapport_financier', targetEntity: LigneFacture::class , cascade: ['remove'])]
    private Collection $lignesFactures;

    #[ORM\ManyToOne(inversedBy: 'rapportsFinanciers')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Entreprises $id_entreprise = null;

    public function getLignesFactures(): Collection
    {
        return $this->lignesFactures;
    }

    public function addLignesFacture(LigneFacture $lignesFacture): static
    {
        if (!$this->lignesFactures->contains($lignesFacture)) {
            $this->lignesFactures->add($lignesFacture);
            $lignesFacture->setIdRapportFinancier($this);
        }

        return $this;
    }

    public function removeLignesFacture(LigneFacture $lignesFacture): static
    {
        if ($this->lignesFactures->removeElement($lignesFacture)) {
            if ($lignesFacture->getIdRapportFinancier() === $this) {
                $lignesFacture->setIdRapportFinancier(null);
            }
        }

        return $this;
    }

    public function getStartDate(): ?\DateTimeImmutable
    {
        return $this->start_date;
    }

    public function setStartDate(\DateTimeImmutable $start_date): static
    {
        $this->start_date = $start_date;

        return $this;
    }

    public function getEndDate(): ?\DateTimeImmutable
    {
        return $this->end_date;
    }

    public function setEndDate(\DateTimeImmutable $end_date): static
    {
        $this->end_date = $end_date;

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

    public function setTotalHt(float $total_ht): static
    {
        $this->total_ht = $total_ht;

        return $this;
    }

    public function getTotalTtc(): ?float
    {
        return $this->total_ttc;
    }

    public function setTotalTtc(float $total_ttc): static
    {
        $this->total_ttc = $total_ttc;

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

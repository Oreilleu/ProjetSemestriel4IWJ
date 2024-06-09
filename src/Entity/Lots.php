<?php

namespace App\Entity;

use App\Repository\LotsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: LotsRepository::class)]
class Lots
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(nullable: true)]
    private ?float $superficie = null;

    #[ORM\Column(length: 255)]
    private ?string $type = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $adresse = null;

    #[ORM\ManyToOne(inversedBy: 'lots')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Clients $id_client = null;

    #[ORM\OneToMany(mappedBy: 'id_lots', targetEntity: Devis::class, orphanRemoval: true)]
    private Collection $devis;

    public function __construct()
    {
        $this->devis = new ArrayCollection();
    }

    public function __toString(): string
    {
        return $this->id_client;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSuperficie(): ?float
    {
        return $this->superficie;
    }

    public function setSuperficie(?float $superficie): static
    {
        $this->superficie = $superficie;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): static
    {
        $this->type = $type;

        return $this;
    }

    public function getAdresse(): ?string
    {
        return $this->adresse;
    }

    public function setAdresse(?string $adresse): static
    {
        $this->adresse = $adresse;

        return $this;
    }

    public function getIdClient(): ?clients
    {
        return $this->id_client;
    }

    public function setIdClient(?clients $id_client): static
    {
        $this->id_client = $id_client;

        return $this;
    }

    /**
     * @return Collection<int, Devis>
     */
    public function getDevis(): Collection
    {
        return $this->devis;
    }

    public function addDevi(Devis $devi): static
    {
        if (!$this->devis->contains($devi)) {
            $this->devis->add($devi);
            $devi->setIdLots($this);
        }

        return $this;
    }

    public function removeDevi(Devis $devi): static
    {
        if ($this->devis->removeElement($devi)) {
            // set the owning side to null (unless already changed)
            if ($devi->getIdLots() === $this) {
                $devi->setIdLots(null);
            }
        }

        return $this;
    }


}

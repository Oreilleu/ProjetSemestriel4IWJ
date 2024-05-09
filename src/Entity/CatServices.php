<?php

namespace App\Entity;

use App\Repository\CatServicesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CatServicesRepository::class)]
class CatServices
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    private ?string $nom = null;

    #[ORM\OneToMany(mappedBy: 'id_cat_services', targetEntity: Services::class, orphanRemoval: true)]
    private Collection $services;

    public function __construct()
    {
        $this->services = new ArrayCollection();
    }

    //On devrait pouvoir récuperer le nom de la catégorie de service avec le get nom ? 
    public function __toString(): string
    {
        return $this->nom ?? '';
    }



    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): static
    {
        $this->nom = $nom;

        return $this;
    }

    /**
     * @return Collection<int, Services>
     */
    public function getServices(): Collection
    {
        return $this->services;
    }

    public function addService(Services $service): static
    {
        if (!$this->services->contains($service)) {
            $this->services->add($service);
            $service->setIdCatServices($this);
        }

        return $this;
    }

    public function removeService(Services $service): static
    {
        if ($this->services->removeElement($service)) {
            // set the owning side to null (unless already changed)
            if ($service->getIdCatServices() === $this) {
                $service->setIdCatServices(null);
            }
        }

        return $this;
    }
}

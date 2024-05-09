<?php

namespace App\Entity;

use App\Repository\ServicesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

//Validate forms

use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ServicesRepository::class)]
class Services
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[Assert\NotBlank(
        message: 'Veuillez renseigner ce champ.'    
    )]
    #[Assert\Length(min: 1, max: 100,
        minMessage: 'Le nom doit contenir au moins {{limit}} caractères.'
    )]
    #[ORM\Column(length: 100)]
    private ?string $nom = null;

    #[Assert\NotBlank(
        message: 'Veuillez renseigner ce champ.'    
    )]
    #[Assert\PositiveOrZero(
        message: 'Le prix ne doit pas être inférieur à 0.'
    )]
    #[ORM\Column]
    private ?float $prix = null;

    #[ORM\ManyToOne(inversedBy: 'services')]
    #[ORM\JoinColumn(nullable: false)]
    private ?CatServices $id_cat_services = null;

    #[ORM\OneToMany(mappedBy: 'id_service', targetEntity: DetailsServices::class, orphanRemoval: true)]
    private Collection $detailsServices;

    public function __construct()
    {
        $this->detailsServices = new ArrayCollection();
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

    public function getPrix(): ?float
    {
        return $this->prix;
    }

    public function setPrix(float $prix): static
    {
        $this->prix = $prix;

        return $this;
    }

    public function getIdCatServices(): ?CatServices
    {
        return $this->id_cat_services;
    }

    public function setIdCatServices(?CatServices $id_cat_services): static
    {
        $this->id_cat_services = $id_cat_services;

        return $this;
    }

    /**
     * @return Collection<int, DetailsServices>
     */
    public function getDetailsServices(): Collection
    {
        return $this->detailsServices;
    }

    public function addDetailsService(DetailsServices $detailsService): static
    {
        if (!$this->detailsServices->contains($detailsService)) {
            $this->detailsServices->add($detailsService);
            $detailsService->setIdService($this);
        }

        return $this;
    }

    public function removeDetailsService(DetailsServices $detailsService): static
    {
        if ($this->detailsServices->removeElement($detailsService)) {
            // set the owning side to null (unless already changed)
            if ($detailsService->getIdService() === $this) {
                $detailsService->setIdService(null);
            }
        }

        return $this;
    }
}

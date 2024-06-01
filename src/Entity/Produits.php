<?php

namespace App\Entity;

use App\Repository\ProduitsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

//Validate forms

use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ProduitsRepository::class)]
class Produits
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[Assert\NotBlank(
        message: 'Veuillez renseigner ce champ.'    
    )]
    #[Assert\Length(
        min: 1, 
        max: 100,
        minMessage: 'Le nom du produit doit contenir au moins {{ limit }} caractères.',
        maxMessage: 'Le nom du produit ne doit pas dépasser {{ limit }} caractères.'
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

    #[ORM\ManyToOne(inversedBy: 'produits')]
    #[ORM\JoinColumn(nullable: true)]
    private ?CategoriesProduits $id_categorie_produits = null;

    #[ORM\OneToMany(mappedBy: 'id_produit', targetEntity: LignesDevis::class)]
    private Collection $lignesDevis;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $filePath = null;

    public function __construct()
    {
        $this->lignesDevis = new ArrayCollection();
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

    public function getIdCategorieProduits(): ?CategoriesProduits
    {
        return $this->id_categorie_produits;
    }

    public function setIdCategorieProduits(?CategoriesProduits $id_categorie_produits): static
    {
        $this->id_categorie_produits = $id_categorie_produits;

        return $this;
    }

    /**
     * @return Collection<int, LignesDevis>
     */
    public function getLignesDevis(): Collection
    {
        return $this->lignesDevis;
    }

    public function addLignesDevi(LignesDevis $lignesDevi): static
    {
        if (!$this->lignesDevis->contains($lignesDevi)) {
            $this->lignesDevis->add($lignesDevi);
            $lignesDevi->setIdProduit($this);
        }

        return $this;
    }

    public function removeLignesDevi(LignesDevis $lignesDevi): static
    {
        if ($this->lignesDevis->removeElement($lignesDevi)) {
            // set the owning side to null (unless already changed)
            if ($lignesDevi->getIdProduit() === $this) {
                $lignesDevi->setIdProduit(null);
            }
        }

        return $this;
    }

    public function getFilePath(): ?string
    {
        return $this->filePath;
    }

    public function setFilePath(?string $filePath): static
    {
        $this->filePath = $filePath;

        return $this;
    }
}

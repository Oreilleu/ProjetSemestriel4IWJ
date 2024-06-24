<?php

namespace App\Entity;

use App\Repository\CategoriesProduitsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

//Validate forms

use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: CategoriesProduitsRepository::class)]
class CategoriesProduits
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
        minMessage: 'Le nom de la catégorie doit contenir au moins {{ limit }} caractères.',
        maxMessage: 'Le nom de la catégorie ne doit pas dépasser {{ limit }} caractères.'
    )]
    #[ORM\Column(length: 100)]
    private ?string $nom = null;
    
    #[ORM\Column(type: "string", length: 255, nullable: true)]
    private $filePath = null;

    #[ORM\OneToMany(mappedBy: 'id_categorie_produits', targetEntity: Produits::class)]
    private Collection $produits;
    
    #[ORM\ManyToOne(targetEntity: Entreprises::class, inversedBy: 'categories')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Entreprises $id_entreprise = null;

    public function __construct()
    {
        $this->produits = new ArrayCollection();
    }

    //On devrait pouvoir récuperer le nom de la catégorie de produit avec le get nom ? 
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
     * @return Collection<int, Produits>
     */
    public function getProduits(): Collection
    {
        return $this->produits;
    }

    public function addProduit(Produits $produit): static
    {
        if (!$this->produits->contains($produit)) {
            $this->produits->add($produit);
            $produit->setIdCategorieProduits($this);
        }

        return $this;
    }

    public function removeProduit(Produits $produit): static
    {
        if ($this->produits->removeElement($produit)) {
            // set the owning side to null (unless already changed)
            if ($produit->getIdCategorieProduits() === $this) {
                $produit->setIdCategorieProduits(null);
            }
        }

        return $this;
    }

    public function getFilePath(): ?string
    {
        return $this->filePath;
    }

    public function setFilePath(?string $filePath): self
    {
        $this->filePath = $filePath;

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
}

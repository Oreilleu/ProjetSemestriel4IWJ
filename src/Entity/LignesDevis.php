<?php

namespace App\Entity;

use App\Repository\LignesDevisRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: LignesDevisRepository::class)]
class LignesDevis
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(nullable: false)]
    private ?string $id_str_produit = null;
    
    #[ORM\Column(nullable: false)]
    private ?string $name_product = null;

    #[ORM\Column(nullable: false)]
    private ?float $prix_product = null;
    
    #[ORM\Column]
    private ?int $quantite = null;

    #[ORM\ManyToOne(inversedBy: 'lignesDevis')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Devis $id_devis = null;

    #[ORM\ManyToOne(inversedBy: 'lignesDevis')]
    #[ORM\JoinColumn(nullable: true)]
    private ?Factures $id_factures = null;

    #[ORM\ManyToOne(inversedBy: 'lignesDevis')]
    #[ORM\JoinColumn(nullable: true, onDelete: 'SET NULL')]
    private ?Produits $id_produit = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): static
    {
        $this->id = $id;

        return $this;
    }

    public function getQuantite(): ?int
    {
        return $this->quantite;
    }

    public function setQuantite(int $quantite): static
    {
        $this->quantite = $quantite;

        return $this;
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

    public function getIdProduit(): ?Produits
    {
        return $this->id_produit;
    }

    public function setIdProduit(?Produits $id_produit): static
    {
        $this->id_produit = $id_produit;

        return $this;
    }


    public function getIdFactures(): ?Factures
    {
        return $this->id_factures;
    }

    public function setIdFactures(?Factures $id_factures): static
    {
        $this->id_factures = $id_factures;

        return $this;
    }

    public function getIdStrProduit(): string
    {
        return $this->id_str_produit;
    }

    public function setIdStrProduit(string $id_str_produit): self
    {
        $this->id_str_produit = $id_str_produit;
        return $this;
    }

    public function getNameProduct(): string
    {
        return $this->name_product;
    }

    public function setNameProduct(string $name_product): self
    {
        $this->name_product = $name_product;
        return $this;
    }

    public function getPrixProduct(): float
    {
        return $this->prix_product;
    }

    public function setPrixProduct(float $prix_product): self
    {
        $this->prix_product = $prix_product;
        return $this;
    }
}

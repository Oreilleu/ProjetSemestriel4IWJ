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

    #[ORM\Column]
    private ?int $quantite = null;

    #[ORM\Column]
    private ?float $prix_unitaire = null;

    #[ORM\ManyToOne(inversedBy: 'lignesDevis')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Devis $id_devis = null;

    #[ORM\ManyToOne(inversedBy: 'lignesDevis')]
    #[ORM\JoinColumn(nullable: false)]
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

    public function getPrixUnitaire(): ?float
    {
        return $this->prix_unitaire;
    }

    public function setPrixUnitaire(float $prix_unitaire): static
    {
        $this->prix_unitaire = $prix_unitaire;

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
}

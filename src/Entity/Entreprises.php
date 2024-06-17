<?php

namespace App\Entity;

use App\Repository\EntreprisesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EntreprisesRepository::class)]
class Entreprises
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    private ?string $nom = null;

    #[ORM\Column(length: 255)]
    private ?string $adresse = null;

    #[ORM\Column(length: 14)]
    private ?string $tel = null;

    #[ORM\Column(length: 100)]
    private ?string $email = null;

    #[ORM\Column(length: 100)]
    private ?string $numero_siret = null;

    #[ORM\Column(length: 100)]
    private ?string $rib = null;

    #[ORM\Column(options:['default' => 'CURRENT_TIMESTAMP'])]
    private ?\DateTimeImmutable $created_at = null;

    #[ORM\OneToMany(mappedBy: 'id_entreprise', targetEntity: User::class)]
    private Collection $users;

    #[ORM\OneToMany(mappedBy: 'id_entreprise', targetEntity: RapportsFinanciers::class, orphanRemoval: true)]
    private Collection $rapportsFinanciers;

    #[ORM\OneToMany(mappedBy: 'id_entreprise', targetEntity: CategoriesProduits::class, cascade: ["remove"])]
    private Collection $categories;

    #[ORM\OneToMany(mappedBy: 'id_entreprise', targetEntity: Produits::class, cascade: ["remove"])]
    private Collection $produits;

    #[ORM\OneToMany(mappedBy: 'id_entreprise', targetEntity: Clients::class, cascade: ["remove"])]
    private Collection $clients;

    public function __construct()
    {
        $this->users = new ArrayCollection();
        $this->created_at = new \DateTimeImmutable();
        $this->rapportsFinanciers = new ArrayCollection();
    }

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

    public function getAdresse(): ?string
    {
        return $this->adresse;
    }

    public function setAdresse(string $adresse): static
    {
        $this->adresse = $adresse;

        return $this;
    }

    public function getTel(): ?string
    {
        return $this->tel;
    }

    public function setTel(string $tel): static
    {
        $this->tel = $tel;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getNumeroSiret(): ?string
    {
        return $this->numero_siret;
    }

    public function setNumeroSiret(string $numero_siret): static
    {
        $this->numero_siret = $numero_siret;

        return $this;
    }

    public function getRib(): ?string
    {
        return $this->rib;
    }

    public function setRib(string $rib): static
    {
        $this->rib = $rib;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->created_at;
    }

    /**
     * @return Collection<int, RapportsFinanciers>
     */
    public function getRapportsFinanciers(): Collection
    {
        return $this->rapportsFinanciers;
    }

    public function addRapportsFinancier(RapportsFinanciers $rapportsFinancier): static
    {
        if (!$this->rapportsFinanciers->contains($rapportsFinancier)) {
            $this->rapportsFinanciers->add($rapportsFinancier);
            $rapportsFinancier->setIdEntreprise($this);
        }

        return $this;
    }

    public function removeRapportsFinancier(RapportsFinanciers $rapportsFinancier): static
    {
        if ($this->rapportsFinanciers->removeElement($rapportsFinancier)) {
            // set the owning side to null (unless already changed)
            if ($rapportsFinancier->getIdEntreprise() === $this) {
                $rapportsFinancier->setIdEntreprise(null);
            }
        }

        return $this;
    }

    public function setCreatedAt(\DateTimeImmutable $created_at): static
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(User $user): static
    {
        if (!$this->users->contains($user)) {
            $this->users->add($user);
            $user->setIdEntreprise($this);
        }

        return $this;
    }

    public function removeUser(User $user): static
    {
        if ($this->users->removeElement($user)) {
            if ($user->getIdEntreprise() === $this) {
                $user->setIdEntreprise(null);
            }
        }

        return $this;
    }

    public function getCategories(): Collection
    {
        return $this->categories;
    }

    public function addCategory(CategoriesProduits $category): self
    {
        if (!$this->categories->contains($category)) {
            $this->categories->add($category);
            $category->setIdEntreprise($this);
        }

        return $this;
    }

    public function removeCategory(CategoriesProduits $category): self
    {
        if ($this->categories->removeElement($category)) {
            // set the owning side to null (unless already changed)
            if ($category->getIdEntreprise() === $this) {
                $category->setIdEntreprise(null);
            }
        }

        return $this;
    }

    public function getProduits(): Collection
    {
        return $this->produits;
    }

    public function addProduit(Produits $produit): self
    {
        if (!$this->produits->contains($produit)) {
            $this->produits->add($produit);
            $produit->setIdEntreprise($this);
        }

        return $this;
    }

    public function removeProduit(Produits $produit): self
    {
        if ($this->produits->removeElement($produit)) {
            // set the owning side to null (unless already changed)
            if ($produit->getIdEntreprise() === $this) {
                $produit->setIdEntreprise(null);
            }
        }

        return $this;
    }

    public function getClients(): Collection
    {
        return $this->clients;
    }

    public function addClient(Clients $client): self
    {
        if (!$this->clients->contains($client)) {
            $this->clients->add($client);
            $client->setIdEntreprise($this);
        }

        return $this;
    }

    public function removeClient(Clients $client): self
    {
        if ($this->clients->removeElement($client)) {
            // set the owning side to null (unless already changed)
            if ($client->getIdEntreprise() === $this) {
                $client->setIdEntreprise(null);
            }
        }

        return $this;
    }

}
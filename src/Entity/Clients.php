<?php

namespace App\Entity;

use App\Repository\ClientsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ClientsRepository::class)]
class Clients
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nom = null;

    #[ORM\Column(length: 255)]
    private ?string $prenom = null;

    #[ORM\Column(length: 255)]
    private ?string $adresse = null;

    #[ORM\Column(length: 255)]
    private ?string $tel = null;

    #[ORM\Column(length: 255)]
    private ?string $email = null;

    #[ORM\Column(length: 255)]
    private ?string $numero_siret = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $cp = null;
    
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $ville = null;
    
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $pays = null;
    
    #[ORM\Column(options:['default' => 'CURRENT_TIMESTAMP'])]
    private ?\DateTimeImmutable $created_at = null;

    #[ORM\ManyToOne(targetEntity: Entreprises::class, inversedBy: 'clients')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Entreprises $id_entreprise = null;

    #[ORM\OneToMany(mappedBy: 'id_client', targetEntity: Lots::class, orphanRemoval: true)]
    private Collection $lots;

    #[ORM\OneToMany(mappedBy: 'id_client', targetEntity: Devis::class, cascade: ["remove"])]
    private Collection $devis;

    #[ORM\OneToMany(mappedBy: 'id_client', targetEntity: Factures::class, cascade: ["remove"])]
    private Collection $facture;

    #[ORM\OneToMany(mappedBy: 'id_client', targetEntity: Interractions::class, cascade: ["remove"])]
    private Collection $interractions;

    public function __construct()
    {
        $this->lots = new ArrayCollection();
        $this->created_at = new \DateTimeImmutable();
        $this->devis = new ArrayCollection();
    }

    public function __toString(): string
    {
        return $this->email ?? '';
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

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): static
    {
        $this->prenom = $prenom;

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

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeImmutable $created_at): static
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function getLots(): Collection
    {
        return $this->lots;
    }

    public function addLot(Lots $lot): static
    {
        if (!$this->lots->contains($lot)) {
            $this->lots->add($lot);
            $lot->setIdClient($this);
        }

        return $this;
    }

    public function removeLot(Lots $lot): static
    {
        if ($this->lots->removeElement($lot)) {
            if ($lot->getIdClient() === $this) {
                $lot->setIdClient(null);
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

    public function getCp(): ?string
    {
        return $this->cp;
    }

    public function setCp(?string $cp): static
    {
        $this->cp = $cp;

        return $this;
    }

    public function getVille(): ?string
    {
        return $this->ville;
    }

    public function setVille(?string $ville): static
    {
        $this->ville = $ville;

        return $this;
    }

    public function getPays(): ?string
    {
        return $this->pays;
    }

    public function setPays(?string $pays): static
    {
        $this->pays = $pays;

        return $this;
    }

    public function getDevis(): Collection
    {
        return $this->devis;
    }

    public function addDevis(Devis $devis): self
    {
        if (!$this->devis->contains($devis)) {
            $this->devis[] = $devis;
            $devis->setClient($this);
        }

        return $this;
    }

    public function removeDevis(Devis $devis): self
    {
        if ($this->devis->removeElement($devis)) {
            // set the owning side to null (unless already changed)
            if ($devis->getClient() === $this) {
                $devis->setClient(null);
            }
        }

        return $this;
    }

    public function getFacture(): Collection
    {
        return $this->facture;
    }

    public function addFacture(Factures $facture): self
    {
        if (!$this->facture->contains($facture)) {
            $this->facture[] = $facture;
            $facture->setClient($this);
        }

        return $this;
    }

    public function removeFacture(Factures $facture): self
    {
        if ($this->devis->removeElement($facture)) {
            if ($facture->getClient() === $this) {
                $facture->setClient(null);
            }
        }

        return $this;
    }

    public function getInterraction(): Collection
    {
        return $this->interractions;
    }

    public function addInterraction(Interractions $interraction): self
    {
        if (!$this->interractions->contains($interraction)) {
            $this->interractions[] = $interraction;
            $interraction->setIdClient($this);
        }

        return $this;
    }

    public function removeInterraction(Interractions $interraction): self
    {
        if ($this->interractions->removeElement($interraction)) {
            // set the owning side to null (unless already changed)
            if ($interraction->getIdClient() === $this) {
                $interraction->setIdClient(null);
            }
        }

        return $this;
    }
}
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

    #[ORM\Column(nullable: true)]
    private ?int $id_devis = null;

    #[ORM\Column(nullable: true)]
    private ?int $id_rapports_financiers = null;

    #[ORM\OneToMany(mappedBy: 'id_entreprise', targetEntity: Users::class, orphanRemoval: true)]
    private Collection $users;

    #[ORM\OneToMany(mappedBy: 'id_entreprise', targetEntity: Devis::class, orphanRemoval: true)]
    private Collection $devis;

    #[ORM\OneToMany(mappedBy: 'id', targetEntity: RapportFinanciers::class, orphanRemoval: true)]
    private Collection $rapportFinanciers;

    public function __construct()
    {
        $this->users = new ArrayCollection();
        $this->devis = new ArrayCollection();
        $this->rapportFinanciers = new ArrayCollection();
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

    public function setCreatedAt(\DateTimeImmutable $created_at): static
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function getIdDevis(): ?int
    {
        return $this->id_devis;
    }

    public function setIdDevis(?int $id_devis): static
    {
        $this->id_devis = $id_devis;

        return $this;
    }

    public function getIdRapportsFinanciers(): ?int
    {
        return $this->id_rapports_financiers;
    }

    public function setIdRapportsFinanciers(?int $id_rapports_financiers): static
    {
        $this->id_rapports_financiers = $id_rapports_financiers;

        return $this;
    }

    /**
     * @return Collection<int, Users>
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(Users $user): static
    {
        if (!$this->users->contains($user)) {
            $this->users->add($user);
            $user->setIdEntreprise($this);
        }

        return $this;
    }

    public function removeUser(Users $user): static
    {
        if ($this->users->removeElement($user)) {
            // set the owning side to null (unless already changed)
            if ($user->getIdEntreprise() === $this) {
                $user->setIdEntreprise(null);
            }
        }

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
            $devi->setIdEntreprise($this);
        }

        return $this;
    }

    public function removeDevi(Devis $devi): static
    {
        if ($this->devis->removeElement($devi)) {
            // set the owning side to null (unless already changed)
            if ($devi->getIdEntreprise() === $this) {
                $devi->setIdEntreprise(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, RapportFinanciers>
     */
    public function getRapportFinanciers(): Collection
    {
        return $this->rapportFinanciers;
    }

    public function addRapportFinancier(RapportFinanciers $rapportFinancier): static
    {
        if (!$this->rapportFinanciers->contains($rapportFinancier)) {
            $this->rapportFinanciers->add($rapportFinancier);
            $rapportFinancier->setId($this);
        }

        return $this;
    }

    public function removeRapportFinancier(RapportFinanciers $rapportFinancier): static
    {
        if ($this->rapportFinanciers->removeElement($rapportFinancier)) {
            // set the owning side to null (unless already changed)
            if ($rapportFinancier->getId() === $this) {
                $rapportFinancier->setId(null);
            }
        }

        return $this;
    }
}

<?php

namespace App\Entity;

use App\Repository\ModePaiementsRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ModePaiementsRepository::class)]
class ModePaiements
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'modePaiements')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Factures $id_facture = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIdFacture(): ?Factures
    {
        return $this->id_facture;
    }

    public function setIdFacture(?Factures $id_facture): static
    {
        $this->id_facture = $id_facture;

        return $this;
    }
}

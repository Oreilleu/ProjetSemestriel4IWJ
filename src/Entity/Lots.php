<?php

namespace App\Entity;

use App\Repository\LotsRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: LotsRepository::class)]
class Lots
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(nullable: true)]
    private ?float $superficie = null;

    #[ORM\Column(length: 255)]
    private ?string $type = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $adresse = null;

    #[ORM\ManyToOne(inversedBy: 'lots')]
    #[ORM\JoinColumn(nullable: false)]
    private ?clients $id_client = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSuperficie(): ?float
    {
        return $this->superficie;
    }

    public function setSuperficie(?float $superficie): static
    {
        $this->superficie = $superficie;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): static
    {
        $this->type = $type;

        return $this;
    }

    public function getAdresse(): ?string
    {
        return $this->adresse;
    }

    public function setAdresse(?string $adresse): static
    {
        $this->adresse = $adresse;

        return $this;
    }

    public function getIdClient(): ?clients
    {
        return $this->id_client;
    }

    public function setIdClient(?clients $id_client): static
    {
        $this->id_client = $id_client;

        return $this;
    }
}

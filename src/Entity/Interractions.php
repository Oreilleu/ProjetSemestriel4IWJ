<?php

namespace App\Entity;

use App\Repository\InterractionsRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: InterractionsRepository::class)]
class Interractions
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;
    
    #[ORM\Column(type: 'text')]
    private ?string $content = null;

    #[ORM\Column(options:['default' => 'CURRENT_TIMESTAMP'])]
    private ?\DateTimeImmutable $created_at = null;
    
    #[ORM\ManyToOne(inversedBy: 'interractions')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Clients $id_client = null;

    #[ORM\ManyToOne(inversedBy: 'interractions')]
    #[ORM\JoinColumn(nullable: true, onDelete: 'SET NULL')]
    private ?Devis $id_devis = null;

    #[ORM\ManyToOne(inversedBy: 'interractions')]
    #[ORM\JoinColumn(nullable: true, onDelete: 'SET NULL')]
    private ?Factures $id_factures = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIdClient(): ?Clients
    {
        return $this->id_client;
    }

    public function setIdClient(?Clients $id_client): static
    {
        $this->id_client = $id_client;

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

    public function getIdFactures(): ?Factures
    {
        return $this->id_factures;
    }

    public function setIdFactures(?Factures $id_factures): static
    {
        $this->id_factures = $id_factures;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): static
    {
        $this->content = $content;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->created_at = $createdAt;

        return $this;
    }
}

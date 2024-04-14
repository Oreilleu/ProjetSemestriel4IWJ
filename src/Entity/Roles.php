<?php

namespace App\Entity;

use App\Repository\RolesRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RolesRepository::class)]
class Roles
{

    #[ORM\Column(length: 100)]
    private ?string $description = null;

    #[ORM\Id]
    #[ORM\ManyToOne(inversedBy: 'roles')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Users $userd_id = null;

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getUserdId(): ?Users
    {
        return $this->userd_id;
    }

    public function setUserdId(?Users $userd_id): static
    {
        $this->userd_id = $userd_id;

        return $this;
    }
}

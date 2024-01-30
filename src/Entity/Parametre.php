<?php

namespace App\Entity;

use App\Repository\ParametreRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ParametreRepository::class)]
class Parametre
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    private ?string $nom_param = null;

    #[ORM\Column(length: 300)]
    private ?string $value_param = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomParam(): ?string
    {
        return $this->nom_param;
    }

    public function setNomParam(string $nom_param): static
    {
        $this->nom_param = $nom_param;

        return $this;
    }

    public function getValueParam(): ?string
    {
        return $this->value_param;
    }

    public function setValueParam(string $value_param): static
    {
        $this->value_param = $value_param;

        return $this;
    }
}

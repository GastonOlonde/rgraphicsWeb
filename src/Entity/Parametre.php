<?php

namespace App\Entity;

use App\Repository\ParametreRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

#[ORM\Entity(repositoryClass: ParametreRepository::class)]
#[Vich\Uploadable]
class Parametre
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100, nullable: true)]
    private ?string $nom_param = null;

    #[ORM\Column(length: 300, nullable: true)]
    private ?string $value_param = null;

    #[Vich\UploadableField(mapping: 'rgraphics_logo', fileNameProperty: 'imageName')]
    private ?File $imageFile = null;

    #[ORM\Column(nullable: true)]
    private ?string $imageName = null;

    #[ORM\Column(length: 500, nullable: true)]
    private ?string $textAccroche = null;

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

    public function getImageFile(): ?File
    {
        return $this->imageFile;
    }

    public function setImageFile(?File $imageFile = null): void
    {
        $this->imageFile = $imageFile;

        if (null !== $imageFile) {
            // It is required that at least one field changes if you are using doctrine
            // otherwise the event listeners won't be called and the file is lost // traduction // 
            $this->updatedAt = new \DateTimeImmutable();
        }
    }

    public function setImageName(?string $imageName): void
    {
        $this->imageName = $imageName;
    }

    public function getImageName(): ?string
    {
        return $this->imageName;
    }

    public function getTextAccroche(): ?string
    {
        return $this->textAccroche;
    }

    public function setTextAccroche(string $textAccroche): static
    {
        $this->textAccroche = $textAccroche;

        return $this;
    }
}

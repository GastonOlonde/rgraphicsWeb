<?php

namespace App\Entity;

use App\Repository\MembreRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Imagine\Gd\Imagine;


#[ORM\Entity(repositoryClass: MembreRepository::class)]
#[Vich\Uploadable]
class Membre
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100, nullable: true)]
    private ?string $nom = null;

    #[ORM\Column(length: 100, nullable: true)]
    private ?string $prenom = null;

    #[ORM\Column(length: 300, nullable: true)]
    private ?string $description = null;

    #[ORM\Column(length: 300, nullable: true)]
    private ?string $role = null;

    #[Vich\UploadableField(mapping: 'rgraphics_membres', fileNameProperty: 'imageName')]
    private ?File $imageFile = null;


    #[ORM\Column(nullable: true)]
    private ?string $imageName = null;

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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getRole(): ?string
    {
        return $this->role;
    }

    public function setRole(string $role): static
    {
        $this->role = $role;

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

    // getOrientation -> imageFile ( récupération de l'orientation de l'image)
    public function getOrientation(): int
    {
        if ($this->imageFile) {// Si une image est associée à l'entité
            $imagePath = $this->imageFile->getPathname();// Récupérez le chemin de l'image
            
            // Utilisez Imagine pour obtenir l'orientation de l'image
            $imagine = new \Imagine\Gd\Imagine();// Créez une instance de l'objet Imagine
            $image = $imagine->open($imagePath);// Ouvrez l'image

            // Obtenez l'orientation (1 signifie que l'image est dans la position normale)
            $orientation = $image->metadata()->get('exif.Orientation', 1);// 1 = portrait, 8 = paysage

            return $orientation;
        }

        // Si aucune image n'est associée, retournez une valeur par défaut (par exemple, 0)
        return 0;
    }
    public function __toString()
    {
        return $this->getPrenom(); // Assurez-vous d'adapter cela en fonction de votre structure réelle
    }
}

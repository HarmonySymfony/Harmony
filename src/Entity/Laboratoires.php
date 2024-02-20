<?php

namespace App\Entity;

use App\Repository\LaboratoiresRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: LaboratoiresRepository::class)]
class Laboratoires
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nom = null;

    #[ORM\Column(length: 255)]
    private ?string $emplacement = null;

    #[ORM\Column(length: 255)]
    private ?string $idU = null;

    #[ORM\Column(length: 255)]
    private ?string $idL = null;

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

    public function getEmplacement(): ?string
    {
        return $this->emplacement;
    }

    public function setEmplacement(string $emplacement): static
    {
        $this->emplacement = $emplacement;

        return $this;
    }

    public function getIdU(): ?string
    {
        return $this->idU;
    }

    public function setIdU(string $idU): static
    {
        $this->idU = $idU;

        return $this;
    }

    public function getIdL(): ?string
    {
        return $this->idL;
    }

    public function setIdL(string $idL): static
    {
        $this->idL = $idL;

        return $this;
    }
}

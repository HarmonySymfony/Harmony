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
    private ?int $idU = null;

    #[ORM\Column(length: 255)]
    private ?int $idL = null;

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

    public function getIdU(): ?int
    {
        return $this->idU;
    }

    public function setIdU(int $idU): static
    {
        $this->idU = $idU;

        return $this;
    }

    public function getIdL(): ?int
    {
        return $this->idL;
    }

    public function setIdL(int $idL): static
    {
        $this->idL = $idL;

        return $this;
    }
}

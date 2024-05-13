<?php

namespace App\Entity;

use App\Repository\PharmacieRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use App\Entity\Medicament;

#[ORM\Entity(repositoryClass: PharmacieRepository::class)]
class Pharmacie
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

     #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message:"Le nom ne peut pas être vide.")]
    private ?string $nom = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message:"L'adresse ne peut pas être vide.")]
    private ?string $adress = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message:"Le type ne peut pas être vide.")]
    private ?string $type = null;





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

    public function getAdress(): ?string
    {
        return $this->adress;
    }

    public function setAdress(string $adress): static
    {
        $this->adress = $adress;

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

}

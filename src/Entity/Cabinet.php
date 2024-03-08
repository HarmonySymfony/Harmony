<?php

namespace App\Entity;

use App\Repository\CabinetRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;





#[ORM\Entity(repositoryClass: CabinetRepository::class)]

class Cabinet
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]

    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\NotBlank(message: "L'adresse est obligatoire !")]
    private ?string $adress = null;
    #[ORM\Column(length: 255, nullable: true)]
    private $nom;
    
    public function getNom(): ?string
    {
        return $this->nom;
    }
    
    public function setNom(string $nom): self
    {
        $this->nom = $nom;
        return $this;
    }


    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\NotBlank(message: "L'horaire de réservation est obligatoire !")]
    #[Assert\Regex(
        pattern: '/^([01]\d|2[0-3]):([0-5]\d)$/', 
        message: "L'heure doit être au format HH:MM"
    )]

    private ?string $horaires = null;
    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\NotBlank(message: " email est obligatoire !")]
    #[Assert\Email(message: " email '{{ value }}' is not a valid email !")]
    private ?string $email = null;

    #[ORM\ManyToOne(inversedBy: 'idC')]
    private ?RendezVous $rendezVous = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAdress(): ?string
    {
        return $this->adress;
    }

    public function setAdress(?string $adress): static
    {
        $this->adress = $adress;

        return $this;
    }

    public function getHoraires(): ?string
    {
        return $this->horaires;
    }

    public function setHoraires(?string $horaires): static
    {
        $this->horaires = $horaires;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): static
    {
        $this->email = $email;

        return $this;
    }
}

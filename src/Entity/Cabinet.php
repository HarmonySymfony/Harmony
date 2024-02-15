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

    private ?string $horaires = null;
    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\NotBlank(message: " email est obligatoire !")]
    #[Assert\Email(message: " email '{{ value }}' is not a valid email !")]
    private ?string $email = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $rendezvous = null;

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

    public function getRendezvous(): ?string
    {
        return $this->rendezvous;
    }

    public function setRendezvous(?string $rendezvous): static
    {
        $this->rendezvous = $rendezvous;

        return $this;
    }
}

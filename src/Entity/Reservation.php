<?php

namespace App\Entity;

use App\Repository\ReservationRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ReservationRepository::class)]
class Reservation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $nbrplace = null;

    #[ORM\Column]
    private ?bool $approuve = null;

    #[ORM\ManyToOne(inversedBy: 'reservations')]
    #[ORM\JoinColumn(name: 'idevent' , referencedColumnName: 'id', onDelete: 'CASCADE' )]

    private ?Evenement $idevent = null;

    
  

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNbrplace(): ?int
    {
        return $this->nbrplace;
    }

    public function setNbrplace(int $nbrplace): static
    {
        $this->nbrplace = $nbrplace;

        return $this;
    }

    public function isApprouve(): ?bool
    {
        return $this->approuve;
    }

    public function setApprouve(bool $approuve): static
    {
        $this->approuve = $approuve;

        return $this;
    }

    public function getIdevent(): ?Evenement
    {
        return $this->idevent;
    }

    public function setIdevent(?Evenement $idevent): static
    {
        $this->idevent = $idevent;

        return $this;
    }

  
}

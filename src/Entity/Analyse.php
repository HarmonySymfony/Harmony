<?php

namespace App\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use App\Repository\AnalyseRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AnalyseRepository::class)]
class Analyse
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: "integer")]
    private $id;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank (message : "le nom est obligatoire") ]   
    private $type;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank (message:"le prix est obligatoire")]
    #[Assert\Positive (message : "le prix est positif") ]  
    private $prix;

    
    #[ORM\ManyToOne( inversedBy:"analyses")]
    #[ORM\JoinColumn(name: "laboratoire_id", referencedColumnName: "id")]
    private Laboratoires $laboratoire;

    /* public function getId(): ?int
    {
        return $this->id;
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

    public function getPrix(): ?float
    {
        return $this->prix;
    }

    public function setPrix(float $prix): static
    {
        $this->prix = $prix;

        return $this;
    }
}
*/


public function getId(): ?int
{
    return $this->id;
}

public function getType(): ?string
{
    return $this->type;
}

public function setType(string $type): self
{
    $this->type = $type;

    return $this;
}

public function getPrix(): ?float
{
    return $this->prix;
}

public function setPrix(float $prix): self
{
    $this->prix = $prix;

    return $this;
}

public function getLaboratoire(): ?Laboratoires
{
    return $this->laboratoire;
}

public function setLaboratoire(?Laboratoires $laboratoire): self
{
    $this->laboratoire = $laboratoire;

    return $this;
}
}
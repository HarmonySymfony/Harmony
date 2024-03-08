<?php

namespace App\Entity;

use App\Repository\RendezVousRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;



#[ORM\Entity(repositoryClass: RendezVousRepository::class)]
class RendezVous
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;
 

   
    #[ORM\Column(length: 255)]
    private ?string $nom = null;

    #[ORM\Column(length: 255)]
    private ?string $prenom = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\DateTime(message: "La date doit être au format Date")]
    private ?string $date = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: " email est obligatoire !")]
    #[Assert\Email(message: " email '{{ value }}' is not a valid email !")]
    private ?string $email = null;

    #[ORM\OneToMany(targetEntity: Cabinet::class, mappedBy: 'rendezVous')]
    private Collection $idC;

    public function __construct()
    {
        $this->idC = new ArrayCollection();
    }

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

    public function getDate(): ?string
    {
        return $this->date;
    }

    public function setDate(string $date): static
    {
        $this->date = $date;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    /**
     * @return Collection<int, Cabinet>
     */
    public function getIdC(): Collection
    {
        return $this->idC;
    }

    public function addIdC(Cabinet $idC): static
    {
        if (!$this->idC->contains($idC)) {
            $this->idC->add($idC);
            $idC->setRendezVous($this);
        }

        return $this;
    }

    public function removeIdC(Cabinet $idC): static
    {
        if ($this->idC->removeElement($idC)) {
            // set the owning side to null (unless already changed)
            if ($idC->getRendezVous() === $this) {
                $idC->setRendezVous(null);
            }
        }

        return $this;
    }
}

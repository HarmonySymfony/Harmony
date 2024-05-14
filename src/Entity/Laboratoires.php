<?php

namespace App\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use App\Repository\LaboratoiresRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: LaboratoiresRepository::class)]
class Laboratoires
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;
    #[ORM\Column(length: 255)]
    #[Assert\NotBlank (message : "le nom est obligatoire") ]  
    #[Assert\Regex(pattern: '/^[a-zA-Z]{3,}$/', message: 'Le nom doit contenir au moins 3 caracteres alphabetiques')]
    private $nom;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank (message : "l/'emplacement est obligatoire") ]  
    private $emplacement;

    //#[ORM\Column(length: 255)]
    //#[Assert\NotBlank (message : "idu est obligatoire") ]  
    //private ?int $idU = null;

    //#[ORM\Column(length: 255)]
    //#[Assert\NotBlank (message : "idL est obligatoire") ]  
    //private ?int $idL = null; 

    /*public function getId(): ?int
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
*/
 


     #[ORM\OneToMany(targetEntity:Analyse::class, mappedBy:"laboratoire", orphanRemoval:true)]
    private Collection $analyses;

    public function __construct()
    {
        $this->analyses = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getEmplacement(): ?string
    {
        return $this->emplacement;
    }

    public function setEmplacement(string $emplacement): self
    {
        $this->emplacement = $emplacement;

        return $this;
    }

    /**
     * @return Collection|Analyse[]
     */
    public function getAnalyses(): Collection
    {
        return $this->analyses;
    }

    public function addAnalyse(Analyse $analyse): self
    {
        if (!$this->analyses->contains($analyse)) {
            $this->analyses[] = $analyse;
            $analyse->setLaboratoire($this);
        }

        return $this;
    }

    public function removeAnalyse(Analyse $analyse): self
    {
        if ($this->analyses->removeElement($analyse)) {
            // set the owning side to null (unless already changed)
            if ($analyse->getLaboratoire() === $this) {
                $analyse->setLaboratoire(null);
            }
        }

        return $this;
    }
    
}
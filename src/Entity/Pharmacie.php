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
    private ?string $idU = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message:"Le nom ne peut pas être vide.")]
    private ?string $nom = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message:"L'adresse ne peut pas être vide.")]
    private ?string $adress = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message:"Le type ne peut pas être vide.")]
    private ?string $type = null;

    #[ORM\ManyToOne(inversedBy: 'pharmacies')]
    private ?Medicament $idMedicament = null;
    private $medicaments;

    public function __construct()
    {
        $this->medicaments = new ArrayCollection();
    }

    // ...

    /**
     * @return Collection|Medicament[]
     */
    public function getMedicaments(): Collection
    {
        return $this->medicaments ?: new ArrayCollection();
    }





    public function getId(): ?int
    {
        return $this->id;
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

    /**
     * @return Collection<int, TableStocks>
     */
    public function getTableStocks(): Collection
    {
        return $this->tableStocks;
    }

    public function addTableStock(TableStocks $tableStock): static
    {
        if (!$this->tableStocks->contains($tableStock)) {
            $this->tableStocks->add($tableStock);
            $tableStock->addIdP($this);
        }

        return $this;
    }

    public function removeTableStock(TableStocks $tableStock): static
    {
        if ($this->tableStocks->removeElement($tableStock)) {
            $tableStock->removeIdP($this);
        }

        return $this;
    }

    public function getIdMedicament(): ?Medicament
    {
        return $this->idMedicament;
    }

    public function setIdMedicament(?Medicament $idMedicament): static
    {
        $this->idMedicament = $idMedicament;

        return $this;
    }
}

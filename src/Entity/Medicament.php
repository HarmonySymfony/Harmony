<?php

namespace App\Entity;

use App\Repository\MedicamentRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: MedicamentRepository::class)]
class Medicament
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message:"La référence ne peut pas être vide.")]
    private ?string $reference = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message:"Le stock ne peut pas être vide.")]
    #[Assert\Type(type:"numeric", message:"Le prix doit être un nombre.")]
    private ?string $stock = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message:"La disponibilité ne peut pas être vide.")]
    private ?string $disponibilite = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message:"La description ne peut pas être vide.")]
    private ?string $description = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message:"Le prix ne peut pas être vide.")]
    #[Assert\Type(type:"numeric", message:"Le prix doit être un nombre.")]
    private ?string $prix = null;

   
    #[ORM\ManyToMany(targetEntity:Pharmacie::class, inversedBy:"medicaments")]
    private $pharmacies;

    public function __construct()
    {
        $this->pharmacies = new ArrayCollection();
    }

    /**
     * @return Collection|Pharmacie[]
     */
    public function getPharmacies(): Collection
    {
        return $this->pharmacies;
    }

    public function addPharmacie(Pharmacie $pharmacie): self
    {
        if (!$this->pharmacies->contains($pharmacie)) {
            $this->pharmacies[] = $pharmacie;
        }

        return $this;
    }

    public function removePharmacie(Pharmacie $pharmacie): self
    {
        $this->pharmacies->removeElement($pharmacie);

        return $this;
    }
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getReference(): ?string
    {
        return $this->reference;
    }

    public function setReference(string $reference): static
    {
        $this->reference = $reference;

        return $this;
    }

    public function getStock(): ?string
    {
        return $this->stock;
    }

    public function setStock(string $stock): static
    {
        $this->stock = $stock;

        return $this;
    }

    public function getDisponibilite(): ?string
    {
        return $this->disponibilite;
    }

    public function setDisponibilite(string $disponibilite): static
    {
        $this->disponibilite = $disponibilite;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getPrix(): ?string
    {
        return $this->prix;
    }

    public function setPrix(string $prix): static
    {
        $this->prix = $prix;

        return $this;
    }

    /**
     * @return Collection<int, Tablestocks>
     */
    public function getTablestocks(): Collection
    {
        return $this->tablestocks;
    }

    public function addTablestock(Tablestocks $tablestock): static
    {
        if (!$this->tablestocks->contains($tablestock)) {
            $this->tablestocks->add($tablestock);
            $tablestock->addIdmedicament($this);
        }

        return $this;
    }

    public function removeTablestock(Tablestocks $tablestock): static
    {
        if ($this->tablestocks->removeElement($tablestock)) {
            $tablestock->removeIdmedicament($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, Pharmacie>
     */

    public function addPharmacy(Pharmacie $pharmacy): static
    {
        if (!$this->pharmacies->contains($pharmacy)) {
            $this->pharmacies->add($pharmacy);
            $pharmacy->setIdMedicament($this);
        }

        return $this;
    }

    public function removePharmacy(Pharmacie $pharmacy): static
    {
        if ($this->pharmacies->removeElement($pharmacy)) {
            // set the owning side to null (unless already changed)
            if ($pharmacy->getIdMedicament() === $this) {
                $pharmacy->setIdMedicament(null);
            }
        }

        return $this;
    }
}

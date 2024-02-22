<?php

namespace App\Entity;

use App\Repository\EvenementRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: EvenementRepository::class)]
class Evenement
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "Veuillez remplir ce champs !")]
    #[Assert\Length(
        min : 2,
        max : 60,
        minMessage : "Your first name must be at least {{ limit }} characters long",
        maxMessage : "Tour first name cannot be longer than {{ limit }} charaters ",
    )]
    private ?string $nom = null;

   

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "Veuillez entrer la description !")]
    private ?string $description = null;

    #[ORM\Column]
    #[Assert\NotBlank( message:"Veuillez entrer le prix !")]
    #[Assert\Type(
        type : "float",
        message : "The value {{ value }} is not a valid {{ type }}."
    )]
    private ?float $prix = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "Veuillez entrer l'adress !")]
    private ?string $adress = null;

    #[ORM\Column( nullable: true)]
    // #[Assert\NotBlank(message: "Veuillez entrer la longitude !")]
    private ?float $longitude = null;

    #[ORM\Column( nullable: true)]
    // #[Assert\NotBlank(message: "Veuillez entrer la latitude !")]
    private ?float $latitude = null;

   
    #[ORM\Column]
    #[Assert\NotBlank(message: "Veuillez entrer la capacité !")]
    private ?int $placeDispo = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable : true)]
    private ?\DateTimeInterface $dateEvent = null;

    #[ORM\OneToMany(mappedBy: 'idevent', targetEntity: Reservation::class)]
    private Collection $reservations;

    #[ORM\Column(length: 255)]
    private ?string $image = null;

    public function __construct()
    {
        $this->reservations = new ArrayCollection();
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

    public function validateFirstName(ExecutionContextInterface $context)
    {
        if ($this->nom !== null && !ctype_upper($this->nom[0])) {
            $context->buildViolation('Your first name must start with an uppercase letter.')
                ->atPath('nom')
                ->addViolation();
        }
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

    public function getPrix(): ?float
    {
        return $this->prix;
    }

    public function setPrix(float $prix): static
    {
        $this->prix = $prix;

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

    public function getLongitude(): ?float
    {
        return $this->longitude;
    }


    public function setLongitude(float $longitude): static
    {
        $this->longitude = $longitude;

        return $this;
    }

    public function getLatitude(): ?float
    {
        return $this->latitude;
    }

    public function setLatitude(float $latitude): static
    {
        $this->latitude = $latitude;

        return $this;
    }


   



    /**
     * Get the value of placeDispo
     */ 
    public function getPlaceDispo()
    {
        return $this->placeDispo;
    }

    /**
     * Set the value of placeDispo
     *
     * @return  self
     */ 
    public function setPlaceDispo($placeDispo)
    {
        $this->placeDispo = $placeDispo;

        return $this;
    }

    public function getDateEvent(): ?\DateTimeInterface
    {
        return $this->dateEvent;
    }

    public function setDateEvent(\DateTimeInterface $dateEvent): static
    {
        $this->dateEvent = $dateEvent;

        
        return $this;
    }

    /**
     * @return Collection<int, Reservation>
     */
    public function getReservations(): Collection
    {
        return $this->reservations;
    }

    public function addReservation(Reservation $reservation): static
    {
        if (!$this->reservations->contains($reservation)) {
            $this->reservations->add($reservation);
            $reservation->setIdevent($this);
        }

        return $this;
    }

    public function removeReservation(Reservation $reservation): static
    {
        if ($this->reservations->removeElement($reservation)) {
            // set the owning side to null (unless already changed)
            if ($reservation->getIdevent() === $this) {
                $reservation->setIdevent(null);
            }
        }

        return $this;
    }

    public function getImage(): string
    {
        return $this->image;
    }

    public function setImage($image): self
    {
        $this->image = $image;

        return $this;
    }
}

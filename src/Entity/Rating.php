<?php

namespace App\Entity;

use App\Repository\RatingRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RatingRepository::class)]
class Rating
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?float $ratingValue = null;

    #[ORM\ManyToOne(inversedBy: 'ratings')]
    private ?Evenement $event = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRatingValue(): ?float
    {
        return $this->ratingValue;
    }

    public function setRatingValue(float $ratingValue): static
    {
        $this->ratingValue = $ratingValue;

        return $this;
    }

    public function getEvent(): ?Evenement
    {
        return $this->event;
    }

    public function setEvent(?Evenement $event): static
    {
        $this->event = $event;

        return $this;
    }
}

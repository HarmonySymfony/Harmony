<?php

namespace App\Entity;

use App\Repository\PostsRepository;
use App\Repository\CommentsRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\DBAL\Types\Types;
use Doctrine\Common\Collections\Collection;



#[ORM\Entity(repositoryClass: PostsRepository::class)]
#[ORM\HasLifecycleCallbacks]
class Posts
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: "AUTO")]
    #[ORM\Column(type: "integer")]
    private int $id;

    #[ORM\Column(type: "text")]
    #[Assert\NotBlank(message: 'Le contenu ne peut pas être vide')]
    #[Assert\Regex(pattern: '/^[a-zA-Z0-9_\s!?.,;:]*$/',
        message: 'Seules les lettres, les chiffres,
     les traits de soulignement et les espaces sont autorisés.')]
    private string $contenu;

    #[ORM\Column(type: "datetime")]
    private \DateTimeInterface $date_creation;

    #[ORM\Column(type: "datetime", nullable: true)]
    private ?\DateTimeInterface $last_modification;
    
    #[ORM\ManyToOne(targetEntity: Utilisateur::class)]
    #[ORM\JoinColumn(name: "utilisateur_id", referencedColumnName: "id", nullable: true)]
    private ?Utilisateur $utilisateur = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: 'Le  ne peut pas être vide')]
    private string $postedAs = 'Anonyme';


    #[ORM\Column(type: Types::JSON, nullable: true)]
    private ?array $likedBy = [];

    #[ORM\Column(type: Types::JSON, nullable: true)]
    private ?array $dislikedBy = [];

    #[ORM\OneToMany(targetEntity: Comments::class, mappedBy: "post")]
    private Collection $comments;

    public function getComments(): Collection
    {
        return $this->comments;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getContenu(): ?string
    {
        return $this->contenu;
    }

    public function setContenu(string $contenu): self
    {
        $this->contenu = $contenu;
        return $this;
    }

    #[ORM\PrePersist]
    public function setCreatedAtValue(): void
    {
        $this->date_creation = new \DateTime();
    }

    public function getDateCreation(): ?\DateTimeInterface
    {
        return $this->date_creation;
    }

    #[ORM\PreUpdate]
    public function setModifiedValue(): void
    {
        $this->last_modification = new \DateTime();
    }

    public function getLastModification(): ?\DateTimeInterface
    {
        return $this->last_modification;
    }

    public function getUtilisateur(): ?Utilisateur
    {
        return $this->utilisateur;
    }

    public function setUtilisateur(?Utilisateur $utilisateur): self
    {
        $this->utilisateur = $utilisateur;
        return $this;
    }

    public function getPostedAs(): ?string
    {
        return $this->postedAs;
    }

    public function setPostedAs(string $postedAs): static
    {
        $this->postedAs = $postedAs;
        return $this;
    }

    public function getLikedBy(): ?array
    {
        return $this->likedBy;
    }

    public function setLikedBy(?array $likedBy): self
    {
        $this->likedBy = $likedBy;
        return $this;
    }

    public function getDislikedBy(): ?array
    {
        return $this->dislikedBy;
    }

    public function setDislikedBy(?array $dislikedBy): self
    {
        $this->dislikedBy = $dislikedBy;
        return $this;
    }
}

<?php

namespace App\Entity;

use App\Repository\PostsRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: PostsRepository::class)]
#[ORM\HasLifecycleCallbacks]
class Posts
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: "AUTO")]
    #[ORM\Column(type: "integer")]
    private ?int $id;

    #[ORM\Column(type: "text")]
    #[Assert\NotBlank(message: 'Le contenu ne peut pas être vide')]
    #[Assert\Regex(pattern: '/^[a-zA-Z0-9_\s!?.]*$/', message: 'Seules les lettres, les chiffres, les traits de soulignement et les espaces sont autorisés.')]
    private ?string $contenu;

    #[ORM\Column(type: "datetime")]
    private \DateTimeInterface $date_creation;


    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(name: "user_id", referencedColumnName: "id")]
    private ?User $user;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: 'Le  ne peut pas être vide')]
    private ?string $postedAs = null;

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

    public function getDateCreation(): ?\DateTimeInterface
    {
        return $this->date_creation;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;
        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    #[ORM\PrePersist]
    public function setCreatedAtValue(): void
    {
        $this->date_creation = new \DateTime();
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
}

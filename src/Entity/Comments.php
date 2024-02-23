<?php

namespace App\Entity;

use App\Repository\CommentsRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: CommentsRepository::class)]
#[ORM\HasLifecycleCallbacks]
class Comments
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: "AUTO")]
    #[ORM\Column(type: "integer")]
    private int $id;

    #[ORM\Column(type: "text")]
    #[Assert\NotBlank(message: 'Le contenu ne peut pas être vide')]
    #[Assert\Regex(pattern: '/^[a-zA-Z0-9_\s!?.,;:]*$/', message: 'Seules les lettres, les chiffres,
     les traits de soulignement et les espaces sont autorisés.')]
    private string $contenu;

    #[ORM\Column(type: "datetime")]
    private \DateTimeInterface $dateCreation;

    #[ORM\Column(type: "datetime", nullable: true)]
    private ?\DateTimeInterface $lastModification;

    #[ORM\ManyToOne(targetEntity: Utilisateur::class)]
    #[ORM\JoinColumn(name: "utilisateur_id", referencedColumnName: "id")]
    private Utilisateur $utilisateur;

    #[ORM\ManyToOne(targetEntity: Posts::class)]
    #[ORM\JoinColumn(name: "posts_id", referencedColumnName: "id")]
    private Posts $post;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: 'Le  ne peut pas être vide')]
    private string $commentedAs = 'Anonyme';

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
        $this->dateCreation = new \DateTime();
    }

    public function getDateCreation(): ?\DateTimeInterface
    {
        return $this->dateCreation;
    }

    #[ORM\PreUpdate]
    public function setModifiedValue(): void
    {
        $this->lastModification = new \DateTime();
    }

    public function getLastModification(): ?\DateTimeInterface
    {
        return $this->lastModification;
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

    public function getPost(): ?Posts
    {
        return $this->post;
    }

    public function setPost(?Posts $post): self
    {
        $this->post = $post;
        return $this;
    }

    public function getCommentedAs(): ?string
    {
        return $this->commentedAs;
    }

    public function setCommentedAs(string $commentedAs): static
    {
        $this->commentedAs = $commentedAs;
        return $this;
    }
}

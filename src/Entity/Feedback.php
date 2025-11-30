<?php

namespace App\Entity;

use App\Repository\FeedbackRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: FeedbackRepository::class)]
#[ORM\Table(name: 'feedback')]
#[ORM\UniqueConstraint(name: 'feedbacks_user_challenge_date_idx', columns: ['author_id', 'challenge_id', 'created_at'])]
class Feedback
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE)]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Assert\NotBlank(message: 'Le contenu ne peut pas être vide')]
    #[Assert\Length(
        min: 10,
        minMessage: 'Votre retour doit contenir au moins {{ limit }} caractères'
    )]
    private ?string $content = null;

    #[ORM\Column(nullable: true)]
    private ?bool $isHelpful = null;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'feedbacks')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $author = null;

    #[ORM\ManyToOne(targetEntity: Challenge::class, inversedBy: 'feedbacks')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Challenge $challenge = null;

    public function __construct()
    {
        $this->createdAt = new \DateTimeImmutable();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): static
    {
        $this->content = $content;

        return $this;
    }

    public function isHelpful(): ?bool
    {
        return $this->isHelpful;
    }

    public function setIsHelpful(?bool $isHelpful): static
    {
        $this->isHelpful = $isHelpful;

        return $this;
    }

    public function getAuthor(): ?User
    {
        return $this->author;
    }

    public function setAuthor(?User $author): static
    {
        $this->author = $author;

        return $this;
    }

    public function getChallenge(): ?Challenge
    {
        return $this->challenge;
    }

    public function setChallenge(?Challenge $challenge): static
    {
        $this->challenge = $challenge;

        return $this;
    }
}

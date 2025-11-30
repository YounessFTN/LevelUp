<?php

namespace App\Entity;

use App\Repository\ChallengeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ChallengeRepository::class)]
class Challenge
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: 'Le titre ne peut pas être vide')]
    #[Assert\Length(
        min: 5,
        max: 255,
        minMessage: 'Le titre doit contenir au moins {{ limit }} caractères',
        maxMessage: 'Le titre ne peut pas dépasser {{ limit }} caractères'
    )]
    private ?string $title = null;
    
    #[ORM\Column(type: Types::TEXT)]
    #[Assert\NotBlank(message: 'La description ne peut pas être vide')]
    #[Assert\Length(
        min: 10,
        minMessage: 'La description doit contenir au moins {{ limit }} caractères'
    )]
    private ?string $description = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTime $creation_date = null;

    #[ORM\Column(length: 255)]
    private ?string $publication_status = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?user $proposer = null;

    /**
     * @var Collection<int, User>
     */
    #[ORM\ManyToMany(targetEntity: User::class)]
    private Collection $moderators;

    /**
     * @var Collection<int, Quote>
     */
    #[ORM\ManyToMany(targetEntity: Quote::class)]
    private Collection $quotes;

    public function __construct()
    {
        $this->moderators = new ArrayCollection();
        $this->quotes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getCreationDate(): ?\DateTime
    {
        return $this->creation_date;
    }

    public function setCreationDate(\DateTime $creation_date): static
    {
        $this->creation_date = $creation_date;

        return $this;
    }

    public function getPublicationStatus(): ?string
    {
        return $this->publication_status;
    }

    public function setPublicationStatus(string $publication_status): static
    {
        $this->publication_status = $publication_status;

        return $this;
    }

    public function getProposer(): ?user
    {
        return $this->proposer;
    }

    public function setProposer(?user $proposer): static
    {
        $this->proposer = $proposer;

        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getModerators(): Collection
    {
        return $this->moderators;
    }

    public function addModerator(User $moderator): static
    {
        if (!$this->moderators->contains($moderator)) {
            $this->moderators->add($moderator);
        }

        return $this;
    }

    public function removeModerator(User $moderator): static
    {
        $this->moderators->removeElement($moderator);

        return $this;
    }

    /**
     * @return Collection<int, Quote>
     */
    public function getQuotes(): Collection
    {
        return $this->quotes;
    }

    public function addQuote(Quote $quote): static
    {
        if (!$this->quotes->contains($quote)) {
            $this->quotes->add($quote);
        }

        return $this;
    }

    public function removeQuote(Quote $quote): static
    {
        $this->quotes->removeElement($quote);

        return $this;
    }
}

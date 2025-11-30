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

    // ✅ CORRECTION : Renommer en camelCase
    #[ORM\Column(name: 'creation_date', type: Types::DATE_MUTABLE)]
    private ?\DateTime $creationDate = null;

    // ✅ CORRECTION : Renommer en camelCase
    #[ORM\Column(name: 'publication_status', length: 255)]
    private ?string $publicationStatus = null;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $proposer = null;

    /**
     * @var Collection<int, User>
     */
    #[ORM\ManyToMany(targetEntity: User::class)]
    private Collection $moderators;

    /**
     * @var Collection<int, Feedback>
     */
    #[ORM\OneToMany(targetEntity: Feedback::class, mappedBy: 'challenge', orphanRemoval: true)]
    private Collection $feedbacks;

    public function __construct()
    {
        $this->moderators = new ArrayCollection();
        $this->feedbacks = new ArrayCollection();
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

    public function setDescription(string $description): static
    {
        $this->description = $description;
        return $this;
    }

    // ✅ CORRECTION : Renommer les méthodes en camelCase
    public function getCreationDate(): ?\DateTime
    {
        return $this->creationDate;
    }

    public function setCreationDate(\DateTime $creationDate): static
    {
        $this->creationDate = $creationDate;
        return $this;
    }

    // ✅ CORRECTION : Renommer les méthodes en camelCase
    public function getPublicationStatus(): ?string
    {
        return $this->publicationStatus;
    }

    public function setPublicationStatus(string $publicationStatus): static
    {
        $this->publicationStatus = $publicationStatus;
        return $this;
    }

    public function getProposer(): ?User
    {
        return $this->proposer;
    }

    public function setProposer(?User $proposer): static
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
     * @return Collection<int, Feedback>
     */
    public function getFeedbacks(): Collection
    {
        return $this->feedbacks;
    }

    public function addFeedback(Feedback $feedback): static
    {
        if (!$this->feedbacks->contains($feedback)) {
            $this->feedbacks->add($feedback);
            $feedback->setChallenge($this);
        }
        return $this;
    }

    public function removeFeedback(Feedback $feedback): static
    {
        if ($this->feedbacks->removeElement($feedback)) {
            if ($feedback->getChallenge() === $this) {
                $feedback->setChallenge(null);
            }
        }
        return $this;
    }
}

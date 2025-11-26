<?php

namespace App\Entity;

use App\Repository\DonationRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DonationRepository::class)]
class Donation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?\DateTime $donation_date = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2)]
    private ?string $amount = null;

    #[ORM\Column(nullable: true)]
    private ?bool $public_display = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $donor = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDonationDate(): ?\DateTime
    {
        return $this->donation_date;
    }

    public function setDonationDate(\DateTime $donation_date): static
    {
        $this->donation_date = $donation_date;

        return $this;
    }

    public function getAmount(): ?string
    {
        return $this->amount;
    }

    public function setAmount(string $amount): static
    {
        $this->amount = $amount;

        return $this;
    }

    public function isPublicDisplay(): ?bool
    {
        return $this->public_display;
    }

    public function setPublicDisplay(?bool $public_display): static
    {
        $this->public_display = $public_display;

        return $this;
    }

    public function getDonor(): ?User
    {
        return $this->donor;
    }

    public function setDonor(?User $donor): static
    {
        $this->donor = $donor;

        return $this;
    }
}

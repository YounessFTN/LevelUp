<?php

namespace App\Entity;

use App\Repository\FundUsageRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: FundUsageRepository::class)]
class FundUsage
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: 'datetime', unique: true)]
    private ?\DateTime $usage_date = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $usage_description = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2)]
    private ?string $amount_used = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsageDate(): ?\DateTime
    {
        return $this->usage_date;
    }

    public function setUsageDate(\DateTime $usage_date): static
    {
        $this->usage_date = $usage_date;

        return $this;
    }

    public function getUsageDescription(): ?string
    {
        return $this->usage_description;
    }

    public function setUsageDescription(string $usage_description): static
    {
        $this->usage_description = $usage_description;

        return $this;
    }

    public function getAmountUsed(): ?string
    {
        return $this->amount_used;
    }

    public function setAmountUsed(string $amount_used): static
    {
        $this->amount_used = $amount_used;

        return $this;
    }
}

<?php

namespace App\Entity;

use App\Repository\QuoteRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: QuoteRepository::class)]
class Quote
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::TEXT, unique: true)]
    private ?string $quote_text = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $author = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTime $added_date = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getQuoteText(): ?string
    {
        return $this->quote_text;
    }

    public function setQuoteText(string $quote_text): static
    {
        $this->quote_text = $quote_text;

        return $this;
    }

    public function getAuthor(): ?string
    {
        return $this->author;
    }

    public function setAuthor(?string $author): static
    {
        $this->author = $author;

        return $this;
    }

    public function getAddedDate(): ?\DateTime
    {
        return $this->added_date;
    }

    public function setAddedDate(?\DateTime $added_date): static
    {
        $this->added_date = $added_date;

        return $this;
    }
}

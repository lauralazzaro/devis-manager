<?php

namespace App\Entity;

use App\Repository\QuoteLineRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\DBAL\Types\Types;

/**
 * Represents a single line item in a quote.
 * Each line has a description, quantity, and unit price.
 * The total for a line is calculated as quantity * unitPrice.
 */
#[ORM\Entity(repositoryClass: QuoteLineRepository::class)]
class QuoteLine
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    /** Description of the service or product */
    #[ORM\Column(type: Types::TEXT)]
    private ?string $description = null;

    /** Number of units (supports decimals, e.g. 2.5 hours) */
    #[ORM\Column]
    private ?float $quantity = null;

    /** Price per unit in euros */
    #[ORM\Column]
    private ?float $unitPrice = null;

    /** The quote this line belongs to */
    #[ORM\ManyToOne(inversedBy: 'quoteLines')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Quote $quote = null;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getQuantity(): ?float
    {
        return $this->quantity;
    }

    public function setQuantity(float $quantity): static
    {
        $this->quantity = $quantity;

        return $this;
    }

    public function getUnitPrice(): ?float
    {
        return $this->unitPrice;
    }

    public function setUnitPrice(float $unitPrice): static
    {
        $this->unitPrice = $unitPrice;

        return $this;
    }

    public function getQuote(): ?Quote
    {
        return $this->quote;
    }

    public function setQuote(?Quote $quote): static
    {
        $this->quote = $quote;

        return $this;
    }

    /**
     * Calculates the total price for this line.
     * Returns null if quantity or unitPrice is not set.
     */
    public function getTotal(): ?float
    {
        if ($this->quantity === null || $this->unitPrice === null) {
            return null;
        }

        return $this->quantity * $this->unitPrice;
    }
}

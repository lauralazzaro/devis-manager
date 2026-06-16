<?php

namespace App\Tests\Unit\Entity;

use App\Entity\QuoteLine;
use PHPUnit\Framework\TestCase;

class QuoteLineTest extends TestCase
{
    public function testTotalCalculation(): void
    {
        $line = new QuoteLine();
        $line->setQuantity(3);
        $line->setUnitPrice(100.0);

        $total = $line->getQuantity() * $line->getUnitPrice();

        $this->assertEquals(300.0, $total);
    }

    public function testZeroQuantity(): void
    {
        $line = new QuoteLine();
        $line->setQuantity(0);
        $line->setUnitPrice(100.0);

        $total = $line->getQuantity() * $line->getUnitPrice();

        $this->assertEquals(0.0, $total);
    }

    public function testDecimalValues(): void
    {
        $line = new QuoteLine();
        $line->setQuantity(2.5);
        $line->setUnitPrice(40.0);

        $total = $line->getQuantity() * $line->getUnitPrice();

        $this->assertEquals(100.0, $total);
    }
}
<?php

namespace App\Tests\Unit\Entity;

use App\Enum\QuoteStatus;
use PHPUnit\Framework\TestCase;

class QuoteStatusTest extends TestCase
{
    public function testQuoteStatusValues(): void
    {
        $this->assertEquals('draft', QuoteStatus::Draft->value);
        $this->assertEquals('sent', QuoteStatus::Sent->value);
        $this->assertEquals('accepted', QuoteStatus::Accepted->value);
        $this->assertEquals('refused', QuoteStatus::Refused->value);
    }

    public function testQuoteStatusCases(): void
    {
        $cases = QuoteStatus::cases();
        $this->assertCount(4, $cases);
    }

    public function testQuoteStatusFromValue(): void
    {
        $status = QuoteStatus::from('draft');
        $this->assertEquals(QuoteStatus::Draft, $status);
    }
}
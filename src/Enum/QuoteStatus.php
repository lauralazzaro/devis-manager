<?php

namespace App\Enum;

/**
 * Represents the possible statuses of a quote.
 *
 * Draft   → quote is being prepared, not yet sent to the client
 * Sent    → quote has been sent to the client, awaiting response
 * Accepted → client has accepted the quote
 * Refused  → client has refused the quote
 */
enum QuoteStatus: string
{
    case Draft = 'draft';
    case Sent = 'sent';
    case Accepted = 'accepted';
    case Refused = 'refused';
}
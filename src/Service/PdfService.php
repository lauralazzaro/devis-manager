<?php

namespace App\Service;

use App\Entity\Quote;
use Spipu\Html2Pdf\Html2Pdf;

/**
 * Service responsible for generating PDF documents from quotes.
 * Uses the Html2Pdf library to convert HTML to PDF output.
 */
class PdfService
{
    /**
     * Generates a PDF for a given quote and returns it as a binary string.
     * The output can be used directly in a Symfony Response with Content-Type: application/pdf.
     */
    public function generateQuotePdf(Quote $quote): string
    {
        // Initialize Html2Pdf in portrait A4 format, French locale
        $html2pdf = new Html2Pdf('P', 'A4', 'fr');

        $html = $this->generateHtml($quote);
        $html2pdf->writeHTML($html);

        // 'S' mode returns the PDF as a string instead of sending it to the browser
        return $html2pdf->output('quote_' . $quote->getQuoteNumber() . '.pdf', 'S');
    }

    /**
     * Builds the HTML content used to generate the PDF.
     * Iterates over quote lines to build the table and calculates the total.
     */
    private function generateHtml(Quote $quote): string
    {
        $html = '
        <page>
            <h1>Quote ' . $quote->getQuoteNumber() . '</h1>
            <p><strong>Client:</strong> ' . $quote->getClient()->getName() . '</p>
            <p><strong>Date:</strong> ' . $quote->getCreatedAt()->format('d/m/Y') . '</p>
            <p><strong>Status:</strong> ' . $quote->getStatus()->value . '</p>
            <table>
                <thead>
                    <tr>
                        <th>Description</th>
                        <th>Quantity</th>
                        <th>Unit Price</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>';

        // Add one row per quote line
        foreach ($quote->getQuoteLines() as $line) {
            $html .= '
                    <tr>
                        <td>' . $line->getDescription() . '</td>
                        <td>' . $line->getQuantity() . '</td>
                        <td>' . $line->getUnitPrice() . ' EUR</td>
                        <td>' . ($line->getQuantity() * $line->getUnitPrice()) . ' EUR</td>
                    </tr>';
        }

        // Calculate the grand total across all lines
        $total = 0;
        foreach ($quote->getQuoteLines() as $line) {
            $total += $line->getQuantity() * $line->getUnitPrice();
        }

        $html .= '
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="3"><strong>Total</strong></td>
                        <td><strong>' . $total . ' EUR</strong></td>
                    </tr>
                </tfoot>
            </table>
        </page>';

        return $html;
    }
}

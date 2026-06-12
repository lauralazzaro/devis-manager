<?php

namespace App\Service;

use App\Entity\Quote;
use Spipu\Html2Pdf\Html2Pdf;

class PdfService
{
    public function generateQuotePdf(Quote $quote): string
    {
        $html2pdf = new Html2Pdf('P', 'A4', 'fr');
        
        $html = $this->generateHtml($quote);
        $html2pdf->writeHTML($html);
        
        return $html2pdf->output('quote_' . $quote->getQuoteNumber() . '.pdf', 'S');
    }

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

        foreach ($quote->getQuoteLines() as $line) {
            $html .= '
                    <tr>
                        <td>' . $line->getDescription() . '</td>
                        <td>' . $line->getQuantity() . '</td>
                        <td>' . $line->getUnitPrice() . ' €</td>
                        <td>' . ($line->getQuantity() * $line->getUnitPrice()) . ' €</td>
                    </tr>';
        }

        $total = 0;
        foreach ($quote->getQuoteLines() as $line) {
            $total += $line->getQuantity() * $line->getUnitPrice();
        }

        $html .= '
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="3"><strong>Total</strong></td>
                        <td><strong>' . $total . ' €</strong></td>
                    </tr>
                </tfoot>
            </table>
        </page>';

        return $html;
    }
}
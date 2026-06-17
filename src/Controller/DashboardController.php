<?php

namespace App\Controller;

use App\Enum\QuoteStatus;
use App\Repository\ClientRepository;
use App\Repository\QuoteRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

/**
 * Controller for the main dashboard page.
 * Displays statistics about clients, quotes, and revenue.
 */
class DashboardController extends AbstractController
{
    /**
     * Renders the dashboard with key business statistics:
     * - Total number of clients
     * - Total number of quotes
     * - Quote count grouped by status
     * - Total revenue from accepted quotes only
     */
    #[Route('/', name: 'app_dashboard')]
    public function index(ClientRepository $clientRepository, QuoteRepository $quoteRepository): Response
    {
        $totalClients = count($clientRepository->findAll());
        $totalQuotes = count($quoteRepository->findAll());

        // Build a map of status => count for all possible statuses
        $quotesByStatus = [];
        foreach (QuoteStatus::cases() as $status) {
            $quotesByStatus[$status->value] = count($quoteRepository->findBy(['status' => $status]));
        }

        // Sum revenue only from accepted quotes
        $totalRevenue = 0;
        foreach ($quoteRepository->findBy(['status' => QuoteStatus::Accepted]) as $quote) {
            foreach ($quote->getQuoteLines() as $line) {
                $totalRevenue += $line->getQuantity() * $line->getUnitPrice();
            }
        }

        return $this->render('dashboard/index.html.twig', [
            'totalClients' => $totalClients,
            'totalQuotes' => $totalQuotes,
            'quotesByStatus' => $quotesByStatus,
            'totalRevenue' => $totalRevenue,
        ]);
    }
}

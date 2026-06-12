<?php

namespace App\Controller;

use App\Enum\QuoteStatus;
use App\Repository\ClientRepository;
use App\Repository\QuoteRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class DashboardController extends AbstractController
{
    #[Route('/', name: 'app_dashboard')]
    public function index(ClientRepository $clientRepository, QuoteRepository $quoteRepository): Response
    {
        $totalClients = count($clientRepository->findAll());
        $totalQuotes = count($quoteRepository->findAll());

        $quotesByStatus = [];
        foreach (QuoteStatus::cases() as $status) {
            $quotesByStatus[$status->value] = count($quoteRepository->findBy(['status' => $status]));
        }

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
<?php

namespace App\Controller;

use App\Entity\Quote;
use App\Form\QuoteType;
use App\Repository\QuoteRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Service\PdfService;
use App\Service\AiService;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Controller for managing quotes (devis).
 * Handles listing, creating, viewing, editing, deleting, and PDF export of quotes.
 */
#[Route('/quote')]
final class QuoteController extends AbstractController
{
    /** Displays the list of all quotes */
    #[Route(name: 'app_quote_index', methods: ['GET'])]
    public function index(QuoteRepository $quoteRepository): Response
    {
        return $this->render('quote/index.html.twig', [
            'quotes' => $quoteRepository->findAll(),
        ]);
    }

    /** Displays and processes the form to create a new quote */
    #[Route('/new', name: 'app_quote_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $quote = new Quote();
        $form = $this->createForm(QuoteType::class, $quote);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($quote);
            $entityManager->flush();

            return $this->redirectToRoute('app_quote_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('quote/new.html.twig', [
            'quote' => $quote,
            'form' => $form,
        ]);
    }

    /** Displays the details of a single quote including its line items */
    #[Route('/{id}', name: 'app_quote_show', methods: ['GET'])]
    public function show(Quote $quote): Response
    {
        return $this->render('quote/show.html.twig', [
            'quote' => $quote,
        ]);
    }

    /**
     * Generates a professional description for a quote line using AI.
     * Returns the generated text as JSON.
     */
    #[Route('/ai/generate-description', name: 'app_quote_ai_description', methods: ['POST'])]
    public function generateDescription(Request $request, AiService $aiService): JsonResponse
    {
        $keyword = $request->getPayload()->getString('keyword');

        if (empty($keyword)) {
            return $this->json(['error' => 'Keyword is required'], 400);
        }

        $description = $aiService->generateQuoteLineDescription($keyword);

        return $this->json(['description' => $description]);
    }

    /**
     * Generates and streams a PDF version of the quote.
     * Uses PdfService to build the PDF from quote data.
     * Returns the file as a downloadable attachment.
     */
    #[Route('/{id}/pdf', name: 'app_quote_pdf', methods: ['GET'])]
    public function pdf(Quote $quote, PdfService $pdfService): Response
    {
        $pdf = $pdfService->generateQuotePdf($quote);

        return new Response($pdf, 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="quote_' . $quote->getQuoteNumber() . '.pdf"',
        ]);
    }

    /** Displays and processes the form to edit an existing quote */
    #[Route('/{id}/edit', name: 'app_quote_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Quote $quote, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(QuoteType::class, $quote);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_quote_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('quote/edit.html.twig', [
            'quote' => $quote,
            'form' => $form,
        ]);
    }

    /**
     * Deletes a quote after validating the CSRF token.
     * Only accessible via POST to prevent accidental deletion.
     */
    #[Route('/{id}', name: 'app_quote_delete', methods: ['POST'])]
    public function delete(Request $request, Quote $quote, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $quote->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($quote);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_quote_index', [], Response::HTTP_SEE_OTHER);
    }
}

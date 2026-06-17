<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

/**
 * Controller for handling locale (language) switching.
 */
class LocaleController extends AbstractController
{
    /**
     * Switches the application language and redirects back to the previous page.
     * The selected locale is stored in the session.
     */
    #[Route('/locale/{locale}', name: 'app_locale_switch', requirements: ['locale' => 'en|fr|it'])]
    public function switchLocale(string $locale, Request $request): Response
    {
        // Store the locale in the session
        $request->getSession()->set('_locale', $locale);

        // Redirect back to the previous page
        return $this->redirect($request->headers->get('referer') ?? '/');
    }
}

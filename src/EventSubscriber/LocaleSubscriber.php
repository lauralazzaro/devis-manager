<?php

namespace App\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;

/**
 * Subscribes to kernel requests to set the locale from the session.
 * This ensures the selected language persists across pages.
 */
class LocaleSubscriber implements EventSubscriberInterface
{
    public function __construct(private string $defaultLocale = 'en')
    {
    }

    /**
     * Reads the locale from the session and applies it to the request.
     * Falls back to the default locale if none is set.
     */
    public function onKernelRequest(RequestEvent $event): void
    {
        $request = $event->getRequest();

        if (!$request->hasPreviousSession()) {
            return;
        }

        $locale = $request->getSession()->get('_locale', $this->defaultLocale);
        $request->setLocale($locale);
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::REQUEST => [['onKernelRequest', 20]],
        ];
    }
}

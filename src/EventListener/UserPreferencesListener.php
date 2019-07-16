<?php

namespace App\EventListener;

use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;

class UserPreferencesListener
{
    private $router;
    private $session;
    private $locales;

    public function __construct(string $locales, SessionInterface $session, RouterInterface $router)
    {
        $this->router = $router;
        $this->session = $session;
        $this->locales = explode('|', $locales);
    }

    public function onInteractiveLogin(InteractiveLoginEvent $event)
    {
        $user = $event->getAuthenticationToken()->getUser();

        $params = [];

        if (null !== $user->getLocale() && in_array($user->getLocale(), $this->locales)) {
            $event->getRequest()->setLocale($user->getLocale());
            $this->session->set('_locale', $user->getLocale());
            $params['_locale'] = $user->getLocale();
        }

        $response = new RedirectResponse($this->router->generate('index', $params));
        $response->send();
    }
}

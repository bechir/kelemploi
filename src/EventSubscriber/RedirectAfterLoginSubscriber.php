<?php

/*
 * This file is part of the Kelemploi application.
 *
 * (c) Bechir Ba <bechiirr71@gmail.com>
 */

namespace App\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;
use Symfony\Component\Security\Http\Util\TargetPathTrait;

class RedirectAfterLoginSubscriber implements EventSubscriberInterface
{
    use TargetPathTrait;

    private $router;

    public function __construct(RouterInterface $router)
    {
        $this->router = $router;
    }

    public function onSecurityInteractiveLogin(InteractiveLoginEvent $event)
    {
        $url = $this->getTargetPath($event->getRequest()->getSession(), 'main');

        if (!$url) {
            $url = $event->getRequest()->request->get('go_to');
        }

        if (!$url) {
            $user = $event->getAuthenticationToken()->getUser();

            $url = $user->hasRole('ROLE_EMPLOYER')
                ? $this->router->generate('company_dashboard')
                : $this->router->generate('user_dashboard');
        }

        $response = new RedirectResponse($url);
        $response->send();
    }

    public static function getSubscribedEvents()
    {
        return [
            'security.interactive_login' => 'onSecurityInteractiveLogin',
        ];
    }
}

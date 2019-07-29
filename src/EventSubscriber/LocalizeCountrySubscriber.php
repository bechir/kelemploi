<?php

/*
 * This file is part of the Kelemploi application.
 *
 * (C) Bechir Ba <bechiirr71@gmail.com>
 */

namespace App\EventSubscriber;

use GeoIp2\Database\Reader;
use GeoIp2\Exception\AddressNotFoundException;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Routing\RouterInterface;

class LocalizeCountrySubscriber implements EventSubscriberInterface
{
    private $container;
    private $router;

    public function __construct(RouterInterface $router, ContainerInterface $container)
    {
        $this->router = $router;
        $this->container = $container;
    }

    public function onKernelRequest(GetResponseEvent $event)
    {
        // $request = $event->getRequest();
        // if ($request->hasPreviousSession()) {
        //     return;
        // }
        //
        // $ip = $request->getClientIp();
        // if (!filter_var($ip, FILTER_VALIDATE_IP)) {
        //     $ip = $request->server->get('REMOTE_ADDR');
        // }
        //
        // $GeoLiteDatabasePath = $this->container->get('kernel')->getPrivateDir().'/GeoLite2City/GeoLite2-City.mmdb';
        //
        // $reader = new Reader($GeoLiteDatabasePath);
        //
        // try {
        //     $record = $reader->city($ip);
        //     $country = strtoupper($record->country->isoCode);
        // } catch (AddressNotFoundException $e) {
        //   throw new \Exception("Error Processing Request");
        // }
        //
        // $response = new RedirectResponse($this->router->generate('index'));
        // $response->send();
    }

    public static function getSubscribedEvents()
    {
        return [
            // must be registered before (i.e. with a higher priority than) the default country listener
            KernelEvents::REQUEST => [['onKernelRequest', 0]],
        ];
    }
}

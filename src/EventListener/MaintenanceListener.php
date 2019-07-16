<?php

namespace App\EventListener;

use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpFoundation\Response;

class MaintenanceListener
{
    private $isLocked;
    private $twig;

    public function __construct($isLocked, \Twig_Environment $twig)
    {
        $this->isLocked = $isLocked;
        $this->twig = $twig;
    }

    public function onKernelRequest(GetResponseEvent $event)
    {
        if (!$this->isLocked) {
            return;
        }

        $page = $this->twig->render('base/maintenance.html.twig');
        $event->setResponse(
            new Response(
                $page,
                Response::HTTP_SERVICE_UNAVAILABLE
            )
        );

        $event->stopPropagation();
    }
}

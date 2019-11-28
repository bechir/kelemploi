<?php

/*
 * This file is part of the Kelemploi application.
 *
 * (c) Bechir Ba <bechiirr71@gmail.com>
 */

namespace App\Event;

use App\Entity\Application;
use Symfony\Component\EventDispatcher\Event;

class ApplicationEvent extends Event
{
    protected $application;

    public function __construct(Application $app)
    {
        $this->application = $app;
    }

    public function getApplication(): Application
    {
        return $this->application;
    }
}

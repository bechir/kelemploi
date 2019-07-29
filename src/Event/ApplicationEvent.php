<?php

/*
 * This file is part of the Kelemploi application.
 *
 * (C) Bechir Ba <bechiirr71@gmail.com>
 */

namespace App\Event;

use App\Entity\Application;
use Symfony\Component\Contracts\Event;

class ApplicationEvent extends Event
{
    protected $application;

    public function __construct(Application $application)
    {
        $this->application = $application;
    }

    public function getApplication(): Application
    {
        return $this->application;
    }

    public function setApplication($application)
    {
        $this->application = $application;
    }

    public function getUser()
    {
        return $this->user;
    }
}

<?php

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

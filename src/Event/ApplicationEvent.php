<?php

namespace App\Event;

use Symfony\Component\Contracts\Event;
use App\Entity\Application;

class ApplicationEvent extends Event
{
    protected $application;

    public function __construct(Application $application)
    {
        $this->application = $application;
    }

    /**
     * @return Application
     */
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

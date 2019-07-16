<?php

namespace App\Event;

use Symfony\Component\EventDispatcher\Event;
use App\Entity\Advert;

class AdvertCreateEvent extends Event
{
    protected $advert;

    public function __construct(Advert $advert)
    {
        $this->advert = $advert;
    }

    /**
     * @return Advert
     */
    public function getAdvert(): Advert
    {
        return $this->advert;
    }

    public function setAdvert($advert)
    {
        $this->advert = $advert;
    }

    public function getUser()
    {
        return $this->user;
    }
}

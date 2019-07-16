<?php

namespace App\Service;

class CountryFinder
{
    private $defaultCountry;

    public function __construct($country)
    {
        $this->defaultCountry = $country;
    }
}

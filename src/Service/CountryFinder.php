<?php

/*
 * This file is part of the Kelemploi application.
 *
 * (c) Bechir Ba <bechiirr71@gmail.com>
 */

namespace App\Service;

class CountryFinder
{
    private $defaultCountry;

    public function __construct($country)
    {
        $this->defaultCountry = $country;
    }
}

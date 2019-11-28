<?php

/*
 * This file is part of the Kelemploi application.
 *
 * (c) Bechir Ba <bechiirr71@gmail.com>
 */

namespace App\Twig;

use Twig\Extension\AbstractExtension;

class HTMLCodeGenerator extends AbstractExtension
{
    private $locale;

    public function __construct(string $locale)
    {
        $this->locale = $locale;
    }

    public function htmlRating()
    {
    }
}

<?php

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

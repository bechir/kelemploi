<?php

/*
 * This file is part of the Kelemploi application.
 *
 * (c) Bechir Ba <bechiirr71@gmail.com>
 */

namespace App\Util;

class CategoryFinder
{
    public function find($name, $categories): bool
    {
        return \in_array($name, explode('|', $categories), true);
    }
}

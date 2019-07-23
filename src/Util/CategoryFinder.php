<?php

namespace App\Util;

class CategoryFinder
{
    public function find($name, $categories) : bool
    {
        return in_array($name, explode('|', $categories));
    }
}

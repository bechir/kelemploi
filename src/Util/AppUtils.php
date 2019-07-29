<?php

/*
 * This file is part of the Kelemploi application.
 *
 * (C) Bechir Ba <bechiirr71@gmail.com>
 */

namespace App\Util;

class AppUtils
{
    public function ContainsNumbers($String)
    {
        return preg_match('/\\d/', $String) > 0;
    }
}

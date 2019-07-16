<?php

namespace App\Utils;

class AppUtils
{
    public function ContainsNumbers($String)
    {
        return preg_match('/\\d/', $String) > 0;
    }
}

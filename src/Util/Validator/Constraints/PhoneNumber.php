<?php

/*
 * This file is part of the Kelemploi application.
 *
 * (C) Bechir Ba <bechiirr71@gmail.com>
 */

namespace App\Util\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * Constraint for the Phone Number validator.
 *
 * @Annotation
 *
 * @author Bechir Ba <bechiirr71@gmail.com>
 */
class PhoneNumber extends Constraint
{
    const INVALID_PHONE_NUMBER_ERROR = '5b4a3cb5-0c3e-13ad-a00f-994bbf3073d';

    public $message = '{{ value }} n\'est pas un numero de telephone valide.';
    public $fields = [];

    protected static $errorNames = [
        self::INVALID_PHONE_NUMBER_ERROR => 'INVALID_PHONE_NUMBER_ERROR',
    ];
}

<?php

namespace App\Utils\Validator\Constraints;

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
    public $fields = array();

    protected static $errorNames = array(
        self::INVALID_PHONE_NUMBER_ERROR => 'INVALID_PHONE_NUMBER_ERROR',
    );
}

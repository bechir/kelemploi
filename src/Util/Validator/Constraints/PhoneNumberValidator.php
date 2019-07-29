<?php

/*
 * This file is part of the Kelemploi application.
 *
 * (C) Bechir Ba <bechiirr71@gmail.com>
 */

namespace App\Util\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

/**
 * @author Bernhard Schussek <bschussek@gmail.com>
 */
class PhoneNumberValidator extends ConstraintValidator
{
    /**
     * {@inheritdoc}
     */
    public function validate($value, Constraint $constraint)
    {
        if (!$constraint instanceof PhoneNumber) {
            throw new UnexpectedTypeException($constraint, __NAMESPACE__ . '\PhoneNumber');
        }

        // custom constraints should ignore null and empty values to allow
        // other constraints (NotBlank, NotNull, etc.) take care of that
        if (null === $value || '' === $value) {
            return;
        }

        $user = $this->context->getObject();
        $method = 'validate_' . $user->getCountry() . '_PhoneNumber';

        if (!method_exists($this, $method)) {
            throw new \Exception(sprintf('%s Country has no validation method', $value));
        }

        if (false === $this->{$method}($value)) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ value }}', $this->formatValue($value))
                ->setCode(PhoneNumber::INVALID_PHONE_NUMBER_ERROR)
                ->addViolation();
        }
    }

    public function validate_MR_PhoneNumber(string $value): bool
    {
        $value = $this->normalize($value);

        // 8 chiffres pour les numeros Mauritaniens
        if (8 !== \mb_strlen($value)) {
            return false;
        }

        if (!\in_array($value[0], ['2', '3', '4'], true)) {
            return false;
        }

        return true;
    }

    public function validate_SN_PhoneNumber(string $value)
    {
        $value = $this->normalize($value);

        // 9 chiffres pour les numeros Senegalais
        if (9 !== \mb_strlen($value)) {
            return false;
        }

        if (7 !== $value[0]) {
            return false;
        }

        return true;
    }

    public function validate_ML_PhoneNumber(string $value)
    {
        return true;
    }

    public function normalize(string $value): string
    {
        return str_replace([' ', '-'], '', $value);
    }
}

<?php

namespace App\Validator;

use App\Entity\User;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class IsValidOwnerValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint)
    {
        /* @var $constraint \App\Validator\IsValidOwner */

        if (null === $value || '' === $value) {
            return;
        }

        // TODO: implement the validation here
        if ($value instanceof User) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ value }}', $value->getEmail())
                ->addViolation();
        } else {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ value }}', "not instance of user")
                ->addViolation();
        }
    }
}

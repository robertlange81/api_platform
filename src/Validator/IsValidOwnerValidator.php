<?php

namespace App\Validator;

use App\Entity\User;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class IsValidOwnerValidator extends ConstraintValidator
{
    /**
     * @var Security
     */
    private $security;
    
    /**
     * IsValidOwnerValidator constructor.
     */
    public function __construct(Security $security)
    {
        $this->security = $security;
    }
    
    public function validate($value, Constraint $constraint)
    {
        /* @var $constraint \App\Validator\IsValidOwner */
        
        if (null === $value || '' === $value) {
            return;
        }
    
        $user = $this->security->getUser();
        if (!$user instanceof User) {
            $this->context->buildViolation($constraint->anonymousMessage)
                ->addViolation();
            return;
        }
    
        // allow admin users to change owners
        if ($this->security->isGranted('ROLE_ADMIN')) {
            return;
        }

        // TODO: implement the validation here
        if ($value instanceof User) {
            if ($value->getId() !== $user->getId()) {
                $this->context->buildViolation($constraint->message)
                    ->addViolation();
            }
        } else {
            throw new \InvalidArgumentException(
                '@IsValidOwner constraint must be put on a property containing a User object'
            );
        }
    }
}

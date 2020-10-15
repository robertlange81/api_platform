<?php

namespace App\Validator;

use App\Entity\User;
use Symfony\Component\HttpFoundation\RequestStack;
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
     * @var RequestStack
     */
    private $request;
    
    /**
     * IsValidOwnerValidator constructor.
     */
    public function __construct(Security $security, RequestStack $request)
    {
        $this->security = $security;
        $this->request = $request;
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
        
        if ($value instanceof User) {
            if ($value->getId() !== $user->getId()) {
                $this->context->buildViolation($constraint->message)
                    ->addViolation();
            }
            
            /*
            if ($this->request->getCurrentRequest()->getMethod() != "POST") {
                $this->context->buildViolation("Update not allowed for normal user.")
                    ->addViolation();
            }
            */
        } else {
            throw new \InvalidArgumentException(
                '@IsValidOwner constraint must be put on a property containing a User object'
            );
        }
    }
}

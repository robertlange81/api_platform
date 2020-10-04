<?php

namespace App\Security\Voter;

use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

class CheeseListingVoter extends Voter
{
    protected function supports($attribute, $subject)
    {
        // replace with your own logic
        // https://symfony.com/doc/current/security/voters.html
        return in_array($attribute, ['ROBERT_PUT_EDIT'])
            && $subject instanceof \App\Entity\CheeseListing;
    }

    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        $user = $token->getUser();
        // if the user is anonymous, do not grant access
        if (!$user instanceof UserInterface) {
            return false;
        }

        // ... (check conditions and return true to grant permission) ...
        switch ($attribute) {
            case 'ROBERT_PUT_EDIT':
                // logic to determine if the user can EDIT
                // return true or false
                if ($subject->getOwner() === $user) {
                    return true;
                }
                
                return false;
        }
    
        throw new \Exception(sprintf('Unhandled attribute "%s"', $attribute));
    }
}

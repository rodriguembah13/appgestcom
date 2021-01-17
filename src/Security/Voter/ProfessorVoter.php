<?php

namespace App\Security\Voter;

use App\Entity\Professor;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class ProfessorVoter extends AbstractVoter
{
    public const ATTRIBUTES = [
        'view', 'edit', 'delete', 'importer', 'details', 'bulletin', 'create',
    ];

    protected function supports($attribute, $subject)
    {
        if (!($subject instanceof Professor)) {
            return false;
        }
        if (!in_array($attribute, self::ATTRIBUTES)) {
            false;
        }

        return true;
    }

    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        $user = $token->getUser();
        // if the user is anonymous, do not grant access
        if (!$user instanceof UserInterface) {
            return false;
        }

        if ($this->hasRolePermission($user, $attribute.'_professor')) {
            return true;
        }

        return false;
    }
}

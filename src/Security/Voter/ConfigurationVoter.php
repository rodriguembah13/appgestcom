<?php

namespace App\Security\Voter;

use App\Entity\Configuration;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

class ConfigurationVoter  extends AbstractVoter
{
    public const ATTRIBUTES = [
        'view', 'edit', 'delete', 'importer', 'details', 'comments', 'create',
    ];

    protected function supports($attribute, $subject)
    {
        if (!($subject instanceof Configuration)) {
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

        if ($this->hasRolePermission($user, $attribute.'_config')) {
            return true;
        }

        return false;
    }
}

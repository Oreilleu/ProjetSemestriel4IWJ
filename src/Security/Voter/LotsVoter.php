<?php

namespace App\Security\Voter;

use App\Entity\Lots;
use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

class LotsVoter extends Voter
{
    public const CREATE = 'CREATE';

    public const EDIT = 'EDIT';
    public const VIEW = 'VIEW';
    public const DELETE = 'DELETE';

    protected function supports(string $attribute, mixed $subject): bool
    {
        // replace with your own logic
        // https://symfony.com/doc/current/security/voters.html
        return in_array($attribute, [self::EDIT, self::VIEW])
            && $subject instanceof \App\Entity\Lots;
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();

        // if the user is anonymous, do not grant access
        if (!$user instanceof UserInterface) {
            return false;
        }

        $lot = $subject;

        if (in_array('ROLE_ADMIN', $user->getRoles())) {
            return true;
        }

        // ... (check conditions and return true to grant permission) ...
        switch ($attribute) {
            case self::CREATE:
            case self::EDIT:
                return $this->canEdit($lot, $user);
            case self::VIEW:
            case self::DELETE:
        }

        return false;
    }

    private function canEdit(Lots $lot, User $user): bool
    {
        return $this->belongsToCompany($lot, $user);
    }
    private function belongsToCompany(Lots $lot, User $user): bool
    {
        return $lot->getIdEntreprise() === $user->getIdEntreprise();
    }
}

<?php

namespace App\Security\Voter;

use App\Entity\Devis;
use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

class DevisVoter extends Voter
{
    public const CREATE = 'CREATE';

    public const EDIT = 'EDIT';
    public const VIEW = 'VIEW';
    public const DELETE = 'DELETE';

    protected function supports(string $attribute, mixed $subject): bool
    {
        // replace with your own logic
        // https://symfony.com/doc/current/security/voters.html
        return in_array($attribute, [self::CREATE, self::EDIT, self::VIEW, self::DELETE])
            && $subject instanceof \App\Entity\Devis;
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();
        // if the user is anonymous, do not grant access
        if (!$user instanceof UserInterface) {
            return false;
        }

        $devis = $subject;
        // ... (check conditions and return true to grant permission) ...
        switch ($attribute) {
            case self::CREATE:
            case self::EDIT:
            case self::VIEW:
                return $this->canView($devis, $user);
            case self::DELETE:
        }

        return false;
    }

    private function canView(Devis $devis, User $user): bool
    {
        return $this->belongsToCompany($devis, $user);
    }

    private function belongsToCompany(Devis $devis, UserInterface $user): bool
    {
        return $devis->getIdEntreprise() === $user->getIdEntreprise();
    }


}

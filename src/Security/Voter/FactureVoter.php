<?php

namespace App\Security\Voter;

use App\Entity\Factures;
use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

class FactureVoter extends Voter
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
            && $subject instanceof \App\Entity\Factures;
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();
        // if the user is anonymous, do not grant access
        if (!$user instanceof UserInterface) {
            return false;
        }

        $facture = $subject;

        // ... (check conditions and return true to grant permission) ...
        switch ($attribute) {
            case self::CREATE:
            case self::EDIT:
            case self::VIEW:
                return $this->canView($facture, $user);
            case self::DELETE:;
        }

        return false;
    }

    private function canView(Factures $facture, User $user): bool
    {
        return $this->belongsToCompany($facture, $user);
    }

    private function belongsToCompany(Factures $facture, User $user): bool
    {
        return $facture->getIdEntreprise() === $user->getIdEntreprise();
    }
}

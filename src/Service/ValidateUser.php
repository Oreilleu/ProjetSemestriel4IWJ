<?php

namespace App\Service;


class ValidateUser
{
    public function validateUserAndDevis($user)
    {
        if (!$user || !$user instanceof \App\Entity\User) {
            return ['route' => 'app_register'];
        }

        return ['user' => $user];
    }
}

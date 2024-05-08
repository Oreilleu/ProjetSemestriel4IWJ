<?php

namespace App\DataFixtures;

use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class UserFixtures extends Fixture
{

    public function __construct(private readonly UserPasswordHasherInterface $passwordHasher) {}

    public function load(ObjectManager $manager): void
    {
        $pwd = 'test';

        $user1 = (new User())
            ->setEmail('user@admin.com')
            ->setRoles(['ROLE_ADMIN'])
        ;
        $user1->setPassword($this->passwordHasher->hashPassword($user1, $pwd));
        $manager->persist($user1);
        $manager->flush();


        $user2 = (new User())
            ->setEmail('user@entreprise.com')
            ->setRoles(['ROLE_ENTREPRISE'])
        ;
        $user2->setPassword($this->passwordHasher->hashPassword($user2, $pwd));
        $manager->persist($user2);
        $manager->flush();
    }
}

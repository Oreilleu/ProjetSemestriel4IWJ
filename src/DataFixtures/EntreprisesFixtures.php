<?php

namespace App\DataFixtures;

use App\Entity\Entreprises;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class EntreprisesFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $company1 = new Entreprises();
        $company1->setNom('Company One');
        $company1->setAdresse('1234 Street, City, Country');
        $company1->setTel('0123456789');
        $company1->setEmail('company1@example.com');
        $company1->setNumeroSiret('12345678901234');
        $company1->setRib('FR7630006000011234567890189');
        $company1->setCreatedAt(new \DateTimeImmutable());
        $manager->persist($company1);

        $this->addReference('company1', $company1);

        $company2 = new Entreprises();
        $company2->setNom('Company Two');
        $company2->setAdresse('5678 Avenue, City, Country');
        $company2->setTel('0987654321');
        $company2->setEmail('company2@example.com');
        $company2->setNumeroSiret('43210987654321');
        $company2->setRib('FR7630006000019876543210123');
        $company2->setCreatedAt(new \DateTimeImmutable());
        $manager->persist($company2);

        $this->addReference('company2', $company2);

        $manager->flush();
    }
}

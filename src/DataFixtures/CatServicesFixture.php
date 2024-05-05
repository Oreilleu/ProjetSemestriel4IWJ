<?php

namespace App\Fixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use App\Entity\CatServices;
use Doctrine\Persistence\ObjectManager;


class CatServicesFixture extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $name = [
            'Ameublement',
            'Peinture',
            'Tableau',
        ];

        for ($i = 0; $i < count($name); $i++) {
            $todo = new CatServices();
            $todo->setNom($name[$i]);

            $manager->persist($todo);
        }

        $manager->flush();
    }
}

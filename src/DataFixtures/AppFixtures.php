<?php

namespace App\DataFixtures;

use App\Fixtures\CatServicesFixture;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $loader = new CatServicesFixture();
        $loader->load($manager);

        $manager->flush();
    }
}

<?php

namespace App\DataFixtures;

use App\Entity\Civility;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class CivilityFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $names = [
            'Mr', 'Mme', 'Mlle'
        ];

        foreach ($names as $name) {
            $civility = new Civility();
            $civility->setAbbr($name);
            $civility->setName($name);

            $manager->persist($civility);
        }

        $manager->flush();
    }
}

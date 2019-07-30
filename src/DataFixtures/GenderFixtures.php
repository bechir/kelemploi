<?php

/*
 * This file is part of the Kelemploi application.
 *
 * (C) Bechir Ba <bechiirr71@gmail.com>
 */

namespace App\DataFixtures;

use App\Entity\Gender;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class GenderFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $genders = [
            'user.gender.male',
            'user.gender.female'
        ];

        foreach ($genders as $name) {
            $gender = (new Gender())
                ->setName($name);
            $manager->persist($gender);
        }

        $manager->flush();
    }
}

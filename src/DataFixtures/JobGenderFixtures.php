<?php

/*
 * This file is part of the Kelemploi application.
 *
 * (C) Bechir Ba <bechiirr71@gmail.com>
 */

namespace App\DataFixtures;

use App\Entity\JobGender;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class JobGenderFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $jobGenders = [
            'user.gender.mf',
            'user.gender.male',
            'user.gender.female',
        ];

        foreach ($jobGenders as $name) {
            $gender = (new JobGender())
                ->setName($name);
            $manager->persist($gender);
        }

        $manager->flush();
    }
}

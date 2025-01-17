<?php

/*
 * This file is part of the Kelemploi application.
 *
 * (C) Bechir Ba <bechiirr71@gmail.com>
 */

namespace App\DataFixtures;

use App\Entity\Experience;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;

class ExperienceFixtures extends Fixture implements FixtureGroupInterface
{
    public function load(ObjectManager $manager)
    {
        $experiences = [
            'user.xp.less1',
            'user.xp.two',
            'user.xp.tree',
            'user.xp.four',
            'user.xp.over5',
        ];

        foreach ($experiences as $xp) {
            $exp = (new Experience())
                ->setName($xp);
            $manager->persist($exp);
        }

        $manager->flush();
    }

    public static function getGroups(): array
    {
        return ['group1'];
    }
}

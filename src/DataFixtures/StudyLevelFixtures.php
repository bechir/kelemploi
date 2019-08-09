<?php

/*
 * This file is part of the Kelemploi application.
 *
 * (C) Bechir Ba <bechiirr71@gmail.com>
 */

namespace App\DataFixtures;

use App\Entity\StudyLevel;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;

class StudyLevelFixtures extends Fixture implements FixtureGroupInterface
{
    public function load(ObjectManager $manager)
    {
        $levels = [
            'user.level.bac',
            'user.level.l1',
            'user.level.l2',
            'user.level.l3',
            'user.level.m1',
            'user.level.m2',
            'user.level.doctorat',
        ];

        foreach ($levels as $l) {
            $level = (new StudyLevel())
                ->setLevel($l);
            $manager->persist($level);
        }

        $manager->flush();
    }

    public static function getGroups(): array
    {
        return ['group1'];
    }
}

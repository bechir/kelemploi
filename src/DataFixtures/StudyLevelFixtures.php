<?php

namespace App\DataFixtures;

use App\Entity\StudyLevel;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class StudyLevelFixtures extends Fixture
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
}

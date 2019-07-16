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
            'Bac',
            'Licence 1',
            'Licence 2',
            'Licence 3',
            'Master 1',
            'Master 2',
            'Doctorat'
        ];

        foreach ($levels as $l) {
            $level = (new StudyLevel())
                ->setLevel($l);
            $manager->persist($level);
        }

        $manager->flush();
    }
}

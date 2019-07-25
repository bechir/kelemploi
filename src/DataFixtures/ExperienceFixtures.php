<?php

namespace App\DataFixtures;

use App\Entity\Experience;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class ExperienceFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $experiences = [
            "job.xp.less1",
            "job.xp.two",
            "job.xp.tree",
            "job.xp.four",
            "job.xp.over5"
        ];

        foreach ($experiences as $xp) {
            $exp = (new Experience())
                ->setName($xp);
            $manager->persist($exp);
        }

        $manager->flush();
    }
}

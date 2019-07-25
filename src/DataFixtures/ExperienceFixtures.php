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
            "user.xp.less1",
            "user.xp.two",
            "user.xp.tree",
            "user.xp.four",
            "user.xp.over5"
        ];

        foreach ($experiences as $xp) {
            $exp = (new Experience())
                ->setName($xp);
            $manager->persist($exp);
        }

        $manager->flush();
    }
}

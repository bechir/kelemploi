<?php

/*
 * This file is part of the Kelemploi application.
 *
 * (c) Bechir Ba <bechiirr71@gmail.com>
 */

namespace App\DataFixtures;

use App\Entity\Region;
use App\Util\AppDirectoriesTrait;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\Persistence\ObjectManager;

class RegionFixtures extends Fixture implements FixtureGroupInterface
{
    use AppDirectoriesTrait;

    public function load(ObjectManager $manager)
    {
        $regions = json_decode(file_get_contents($this->getVarApp() . '/job-data.json'), true)['job']['regions'];

        foreach ($regions as $regionName) {
            $region = (new Region())
                ->setName($regionName);

            $manager->persist($region);
        }

        $manager->flush();
    }

    public static function getGroups(): array
    {
        return ['group1'];
    }
}

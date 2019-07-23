<?php

namespace App\DataFixtures;

use App\Entity\Region;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

use App\Util\AppDirectoriesTrait;

class RegionFixtures extends Fixture
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
}

<?php

/*
 * This file is part of the Kelemploi application.
 *
 * (c) Bechir Ba <bechiirr71@gmail.com>
 */

namespace App\DataFixtures;

use App\Entity\JobCategory;
use App\Util\AppDirectoriesTrait;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\Persistence\ObjectManager;

class JobCategoryFixtures extends Fixture implements FixtureGroupInterface
{
    use AppDirectoriesTrait;

    public function load(ObjectManager $manager)
    {
        $categories = json_decode(file_get_contents($this->getVarApp() . '/job-data.json'), true)['job']['categories'];

        foreach ($categories as $categoryName) {
            $jobCategory = (new JobCategory())
                ->setName($categoryName);

            $manager->persist($jobCategory);
        }

        $manager->flush();
    }

    public static function getGroups(): array
    {
        return ['group1'];
    }
}

<?php

namespace App\DataFixtures;

use App\Entity\JobCategory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class JobCategoryFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $categories = [
            'Banque & Finance',
            'Commerce',
            'Génie électrique',
            'Génie mécanique',
            'Marketing',
            'Informatique',
            'Autres'
        ];

        foreach ($categories as $categoryName) {
            $jobCategory = (new JobCategory())
                ->setName($categoryName);
            
            $manager->persist($jobCategory);
        }

        $manager->flush();
    }
}

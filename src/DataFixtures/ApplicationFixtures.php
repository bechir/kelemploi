<?php

/*
 * This file is part of the Kelemploi application.
 *
 * (C) Bechir Ba <bechiirr71@gmail.com>
 */

namespace App\DataFixtures;

use App\Entity\Application;
use App\Entity\ContractType;
use App\Entity\DateInterval;
use App\Entity\JobCategory;
use App\Entity\Region;
use App\Entity\StudyLevel;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory;
use Faker\Generator;
use Faker\Provider\Base;
use Faker\Provider\DateTime;
use Faker\Provider\fr_FR\Company;
use Faker\Provider\fr_FR\Text;
use Faker\Provider\Lorem;

class ApplicationFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        // $this->loadApplications($manager);
    }

    public function loadApplications(ObjectManager $manager)
    {
        $regions = $manager->getRepository(Region::class)->findAll();
        $jobCategories = $manager->getRepository(JobCategory::class)->findAll();
        $contracts = $manager->getRepository(ContractType::class)->findAll();
        $studyLevels = $manager->getRepository(StudyLevel::class)->findAll();

        $faker = Factory::create('fr_FR');
        $text = new Text(new Generator());

        for ($i = 0; $i < 30; ++$i) {
            $dateInterval = (new DateInterval())
                ->setStart(DateTime::dateTimeThisYear());
            $salary = $faker->numerify('###000 FCFA');

            $application = (new Application())
                ->setCompany(Company::companySuffix())
                ->setPostCategory(Base::randomElement($jobCategories))
                ->setNbCandidatesToRecruit($faker->randomDigitNotNull)
                ->setJobTitle($faker->word)
                ->setCompanyDescription($text->realText())
                ->setJobDescription(Lorem::paragraph() . Lorem::paragraph())
                ->setComment(Lorem::paragraph())
                ->setProfile($text->realText())
                ->setContractType(Base::randomElement($contracts))
                ->setDates($dateInterval)
                ->setRegion($faker->randomElement($regions))
                ->setMinStudyLevel($faker->randomElement($studyLevels))
                ->setSalary($salary)
            ;

            $manager->persist($application);
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            ContractTypeFixtures::class,
            JobCategoryFixtures::class,
            StudyLevelFixtures::class,
            RegionFixtures::class,
        ];
    }
}

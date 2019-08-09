<?php

/*
 * This file is part of the Kelemploi application.
 *
 * (C) Bechir Ba <bechiirr71@gmail.com>
 */

namespace App\DataFixtures;

use App\Entity\ContractType;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;

class ContractTypeFixtures extends Fixture implements FixtureGroupInterface
{
    public function load(ObjectManager $manager)
    {
        $contracts = [
            'job.contract.cdd',
            'job.contract.cdi',
            'job.contract.freelance',
            'job.contract.stage',
            'job.contract.alternance',
        ];

        foreach ($contracts as $contract) {
            $contractType = new ContractType();
            $contractType->setName($contract);

            $manager->persist($contractType);
        }

        $manager->flush();
    }

    public static function getGroups(): array
    {
        return ['group1'];
    }
}

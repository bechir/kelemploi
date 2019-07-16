<?php

namespace App\DataFixtures;

use App\Entity\ContractType;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class ContractTypeFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $contracts = [
            'CDD', 'CDI', 'Stage', 'Alternance'
        ];

        foreach ($contracts as $contract) {
            $contractType = new ContractType();
            $contractType->setName($contract);

            $manager->persist($contractType);
        }

        $manager->flush();
    }
}

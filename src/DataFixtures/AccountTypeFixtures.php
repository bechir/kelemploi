<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use App\Entity\AccountType;

class AccountTypeFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $types = [
            'app.candidate',
            'app.employer',
        ];

        foreach($types as $name) {
            $type = new AccountType();
            $type->setName($name);

            $manager->persist($type);
        }

        $manager->flush();
    }
}

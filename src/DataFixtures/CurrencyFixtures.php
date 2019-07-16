<?php

namespace App\DataFixtures;

use App\Entity\Currency;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $currencies = ['MRU', 'EUR', 'USD', 'CAD', 'TND', 'MAD', 'XOF'];
        
        foreach ($currencies as $code) {
            $currency = new Currency();
            $currency->setCode($code);

            $manager->persist($currency);
        }
        
        $manager->flush();
    }
}

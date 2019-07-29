<?php

/*
 * This file is part of the Kelemploi application.
 *
 * (C) Bechir Ba <bechiirr71@gmail.com>
 */

namespace App\DataFixtures;

use App\Entity\Currency;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class CurrencyFixtures extends Fixture
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

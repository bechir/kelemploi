<?php

/*
 * This file is part of the Kelemploi application.
 *
 * (c) Bechir Ba <bechiirr71@gmail.com>
 */

namespace App\DataFixtures;

use App\Entity\Industry;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class IndustryFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        foreach ($this->getNames() as $name) {
            $industry = (new Industry())
                ->setName($name);
            $manager->persist($industry);
        }

        $manager->flush();
    }

    public function getNames(): array
    {
        return [
            'Agroalimentaire',
            'Banque / Assurance',
            'Bois / Papier / Carton / Imprimerie',
            'BTP / Matériaux de construction',
            'Chimie / Parachimie',
            'Commerce / Négoce / Distribution',
            'Édition / Communication / Multimédia',
            'Électronique / Électricité',
            'Études et conseils',
            'Industrie pharmaceutique',
            'Informatique / Télécoms',
            'Machines et équipements / Automobile',
            'Métallurgie / Travail du métal',
            'Plastique / Caoutchouc',
            'Services aux entreprises',
            'Textile / Habillement / Chaussure',
            'Transports / Logistique',
        ];
    }
}

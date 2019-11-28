<?php

/*
 * This file is part of the Kelemploi application.
 *
 * (c) Bechir Ba <bechiirr71@gmail.com>
 */

namespace App\DataFixtures;

use App\Entity\Contest;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityManagerInterface;

class ContestFixtures extends Fixture
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function load(ObjectManager $manager)
    {
        $author = $this->em->getRepository(User::class)->findOneByEmail('bechiirr71@gmail.com');

        if ($author) {
            foreach ($this->getContestTitles() as $title) {
                $contest = (new Contest())
                    ->setTitle($title)
                    ->setActivated(true)
                    ->setDescription($title)
                    ->setAuthor($author);

                $manager->persist($contest);
            }

            $manager->flush();
        }
    }

    public function getContestTitles(): array
    {
        return [
            'Concours Sous-Officiers de la Gendarmerie – répartitions des candidats résidants à Dakar pour les épreuves physiques',
            'Concours d’entrée en 1ère année de BTS en mécatronique',
            'Les résultats du concours d’entrée à l’ISFAR de Bambey',
            'Résultats définitifs au concours CFMPL Technicien Supérieur 2019',
            'Communiqué de la FASTEF a l’attention des admis au concours d’entrée 2019',
            'Concours d’entrée au lycée scientifique d’excellence de Diourbel : Date des épreuves',
            'Communiqué portant sur le concours d’entrée à la Maison d’Education Mariama BA de Gorée – Session 2019',
            'Résultats du concours professionnel d’entrée au premier cycle de l’ENTSS',
            'Résultats du concours professionnel d’entrée au second cycle de l’ENTSS',
            'Concours d’entrée en 1ère année de Licence en Informatique à la FST-UCAD',
        ];
    }
}

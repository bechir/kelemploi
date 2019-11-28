<?php

/*
 * This file is part of the Kelemploi application.
 *
 * (c) Bechir Ba <bechiirr71@gmail.com>
 */

namespace App\EventSubscriber;

use App\Event\ApplicationEvent;
use App\Util\CategoryCounter;
use App\Util\RegionCounter;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class ApplicationSubscriber implements EventSubscriberInterface
{
    /**
     * @var EntityManager
     */
    protected $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function onApplicaionViewed(ApplicationEvent $event)
    {
        $application = $event->getApplication();

        $application->increaseViewCount();

        $this->em->persist($application);
        $this->em->flush();
    }

    public function onApplicaionCreated(ApplicationEvent $event)
    {
        $application = $event->getApplication();

        if (null !== $application->getPostCategory()) {
            CategoryCounter::increment($application->getPostCategory()->getSlug());
        }
        if (null !== $application->getCompany()->getRegion()) {
            RegionCounter::increment($application->getCompany()->getRegion()->getSlug());
        }
    }

    public function onApplicaionDeleted(ApplicationEvent $event)
    {
        $application = $event->getApplication();

        if (null !== $application->getPostCategory()) {
            CategoryCounter::decrement($application->getPostCategory()->getSlug());
        }
        if (null !== $application->getCompany()->getRegion()) {
            RegionCounter::decrement($application->getCompany()->getRegion()->getSlug());
        }
    }

    public static function getSubscribedEvents()
    {
        return [
            'application.viewed' => 'onApplicaionViewed',
            'application.created' => 'onApplicaionCreated',
            'application.deleted' => 'onApplicaionDeleted',
        ];
    }
}

/*

SELECT e.nom, e.prenom
FROM Loueur e, Vehivule v, Location l
WHERE e.id = l.loueurId
    v.id = l.vehiculeId
    AND v.type = 'camionnette';


SELECT nom, prenom
FROM Loueur
WHERE loeurId NOT IN(
    SELECT E.loueurId
    FROM Loueur E, Vehicule V, Location L
    WHERE TO_CHAR(E.dateInactif, 'DD/MM/YYYY') IS NOT NULL
        AND V.vehiculeId = L.vehiculeId
        AND E.loueurId = L.loueurId
        AND V.type = 'voiture'
        AND TO_CHAR(L.dateDebutLocation, 'YYYY') = '2016'
)



*/

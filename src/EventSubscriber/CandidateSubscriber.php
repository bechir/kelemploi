<?php

namespace App\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use App\Event\CandidateEvent;
use Doctrine\ORM\EntityManagerInterface;

class CandidateSubscriber implements EventSubscriberInterface
{

    /**
     * @var EntityManager
     */
    protected $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function onCandidateViewed(CandidateEvent $event)
    {
        $candidate = $event->getCandidate();

        $candidate->increaseViewCount();

        $this->em->persist($candidate);
        $this->em->flush();
    }

    public static function getSubscribedEvents()
    {
        return [
            'candidate.viewed' => 'onCandidateViewed',
        ];
    }
}

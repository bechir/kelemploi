<?php

/*
 * This file is part of the Kelemploi application.
 *
 * (C) Bechir Ba <bechiirr71@gmail.com>
 */

namespace App\EventSubscriber;

use App\Entity\Comment;
use App\Entity\Establishment\BasicInfo;
use App\Entity\Note;
use App\Events;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\EventDispatcher\GenericEvent;

/**
 * Update hotel note.
 *
 * @author Bechir Ba <bechiirr71@gmail.com>
 */
class CommentNoteSubscriber implements EventSubscriberInterface
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            Events::COMMENT_CREATED => 'onCommentCreated',
        ];
    }

    public function onCommentCreated(GenericEvent $event): void
    {
        /** @var Comment $comment */
        $comment = $event->getSubject();

        /** @var BasicInfo $estab */
        $estab = $event->getArgument('estab');

        /** @var Note|null $note */
        $note = $estab->getNote();

        if (null === $note) {
            $note = new Note();
        }

        $rate = $note->getRate();
        $numberReviews = $note->getNumberReviews();

        $rate = ($rate * $numberReviews + $comment->getNote() * 2) / ($numberReviews + 1);
        $rate = $note->setRate($rate)->getRate();

        $title = '';

        // Finding the title that correspond to the rate
        if ($rate >= 0 && $rate <= 2.9) {
            $title = Note::VERY_BAD;
        } elseif ($rate >= 3 && $rate <= 4.9) {
            $title = Note::BAD;
        } elseif ($rate >= 5 && $rate <= 5.9) {
            $title = Note::FAIR;
        } elseif ($rate >= 6 && $rate <= 7.9) {
            $title = Note::GOOD;
        } elseif ($rate >= 8 && $rate <= 8.9) {
            $title = Note::VERY_GOOD;
        } elseif ($rate >= 9 && $rate <= 10) {
            $title = Note::EXCELLENT;
        } else {
            throw new \Exception(sprintf('The rate must be between 0 and 10 (%s found)', $rate));
        }

        $note->setTitle($title);
        $note->increment();

        $estab->setNote($note);

        $this->em->persist($estab);
        $this->em->flush();
    }
}

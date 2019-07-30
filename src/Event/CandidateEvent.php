<?php

namespace App\Event;

use App\Entity\User;
use Symfony\Component\EventDispatcher\Event;

class CandidateEvent extends Event
{
    protected $candidate;

    public function __construct(User $user)
    {
        $this->candidate = $user;
    }

    public function getCandidate(): User
    {
        return $this->candidate;
    }
}

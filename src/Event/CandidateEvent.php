<?php

/*
 * This file is part of the Kelemploi application.
 *
 * (c) Bechir Ba <bechiirr71@gmail.com>
 */

namespace App\Event;

use App\Entity\User;
use Symfony\Contracts\EventDispatcher\Event;

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

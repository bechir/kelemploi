<?php

namespace App;

/**
 * This class defines the names of all the events dispatched in
 * the web application.
 *
 * @author Bechir Ba <bechiirr71@gmail.com>
 */
final class Events
{
    /**
     * @Event("Symfony\Component\EventDispatcher\GenericEvent")
     *
     * @var string
     */
    public const COMMENT_CREATED = 'comment.created';
}

<?php

/*
 * This file is part of the Kelemploi application.
 *
 * (C) Bechir Ba <bechiirr71@gmail.com>
 */

namespace App\Event;

/**
 * This class defines the names of all the events dispatched in the application.
 */
final class AppEvents
{
    /**
     * For the event naming conventions, see:
     * https://symfony.com/doc/current/components/event_dispatcher.html#naming-conventions.
     *
     * @Event("Symfony\Component\EventDispatcher\GenericEvent")
     *
     * @var string
     */
    public const onUserCreate = 'user.created';

    /**
     * @var string
     */
    public const onUserActivate = 'user.activated';

    /**
     * @var string
     */
    public const onUserDesactivate = 'user.desactivated';

    /**
     * @var string
     */
    public const OnUserDelete = 'user.deleted';

    /**
     * @var string
     */
    public const onAdvertCreate = 'advert.creaated';

    /**
     * @var string
     */
    public const onAdvertDelete = 'advert.deleted';

    /**
     * @var string
     */
    public const onShopCreate = 'shop.creaated';

    /**
     * @var string
     */
    public const onShopDelete = 'shop.deleted';
}

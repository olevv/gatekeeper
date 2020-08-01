<?php

declare(strict_types=1);

namespace App\Infrastructure\Shared\Bus\Event;

use App\Domain\Shared\Event\DomainEvent;

final class Event implements EventInterface
{
    private DomainEvent $event;

    public function __construct(DomainEvent $event)
    {
        $this->event = $event;
    }

    public function getDomainEvent(): DomainEvent
    {
        return $this->event;
    }
}

<?php

declare(strict_types=1);

namespace App\Domain\Shared\Event;

use DateTimeInterface;
use Ramsey\Uuid\UuidInterface;

abstract class DomainEvent
{
    private UuidInterface $aggregateId;

    private UuidInterface $eventId;

    private \DateTimeImmutable $occurredOn;

    public function __construct(UuidInterface $aggregateId, UuidInterface $eventId, \DateTimeImmutable $occurredOn)
    {
        $this->aggregateId = $aggregateId;
        $this->eventId = $eventId;
        $this->occurredOn = $occurredOn;
    }

    abstract public function name(): string;

    abstract public function payload(): array;

    final public function aggregateId(): string
    {
        return $this->aggregateId->toString();
    }

    final public function eventId(): string
    {
        return $this->eventId->toString();
    }

    final public function occurredOn(): string
    {
        return $this->occurredOn->format(DateTimeInterface::ATOM);
    }
}

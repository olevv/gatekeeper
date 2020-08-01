<?php

declare(strict_types=1);

namespace App\Domain\User\Events;

use App\Domain\Shared\Event\DomainEvent;
use Ramsey\Uuid\UuidInterface;

final class UserEmailChanged extends DomainEvent
{
    private string $email;

    public function __construct(string $email, UuidInterface $aggregateId, UuidInterface $eventId, \DateTimeImmutable $occurredOn)
    {
        parent::__construct($aggregateId, $eventId, $occurredOn);
        $this->email = $email;
    }

    public function eventName(): string
    {
        return 'user_email_changed';
    }

    public function payload(): array
    {
        // TODO: Implement serialize() method.
    }
}

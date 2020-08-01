<?php

declare(strict_types=1);

namespace App\Domain\User\Event;

use App\Domain\Shared\Event\DomainEvent;
use App\Domain\User\ValueObject\Auth\Credentials;
use App\Domain\User\ValueObject\Role;
use App\Domain\User\ValueObject\Status;
use Ramsey\Uuid\UuidInterface;

final class UserWasCreated extends DomainEvent
{
    private Credentials $credentials;
    private Role $role;
    private Status $status;

    public function __construct(
        Credentials $credentials,
        Role $role,
        Status $status,
        UuidInterface $aggregateId,
        UuidInterface $eventId,
        \DateTimeImmutable $occurredOn
    ) {
        parent::__construct($aggregateId, $eventId, $occurredOn);
        $this->credentials = $credentials;
        $this->role = $role;
        $this->status = $status;
    }

    public function eventName(): string
    {
        return 'user_was_created';
    }

    public function payload(): array
    {
        return [
            'email' => $this->credentials->email->toString(),
            'role' => $this->role->toString(),
            'status' => $this->status->toString(),
            'password' => $this->credentials->password->toString(),
        ];
    }
}

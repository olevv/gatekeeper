<?php

declare(strict_types=1);

namespace App\Domain\User\Event;

use App\Domain\Shared\Event\DomainEvent;
use App\Domain\User\ValueObject\Role;
use Ramsey\Uuid\UuidInterface;

final class UserRoleChanged extends DomainEvent
{
    private Role $role;

    public function __construct(Role $role, UuidInterface $aggregateId, UuidInterface $eventId, \DateTimeImmutable $occurredOn)
    {
        parent::__construct($aggregateId, $eventId, $occurredOn);
        $this->role = $role;
    }

    public function name(): string
    {
        return 'user_role_changed';
    }

    public function payload(): array
    {
        return [
            'role' => $this->role->toString(),
        ];
    }
}

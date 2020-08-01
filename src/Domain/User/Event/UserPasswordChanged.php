<?php

declare(strict_types=1);

namespace App\Domain\User\Event;

use App\Domain\Shared\Event\DomainEvent;
use App\Domain\User\ValueObject\Auth\HashedPassword;
use Ramsey\Uuid\UuidInterface;

final class UserPasswordChanged extends DomainEvent
{
    private HashedPassword $password;

    public function __construct(
        HashedPassword $password,
        UuidInterface $aggregateId,
        UuidInterface $eventId,
        \DateTimeImmutable $occurredOn
    ) {
        parent::__construct($aggregateId, $eventId, $occurredOn);
        $this->password = $password;
    }

    public function name(): string
    {
        return 'user_password_changed';
    }

    public function payload(): array
    {
        return [
            'password' => $this->password->toString(),
        ];
    }
}

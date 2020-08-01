<?php

declare(strict_types=1);

namespace App\Domain\User\Event;

use App\Domain\Shared\Event\DomainEvent;
use App\Domain\User\ValueObject\Email;
use Ramsey\Uuid\UuidInterface;

final class UserSignedIn extends DomainEvent
{
    private Email $email;

    public function __construct(Email $email, UuidInterface $aggregateId, UuidInterface $eventId, \DateTimeImmutable $occurredOn)
    {
        parent::__construct($aggregateId, $eventId, $occurredOn);
        $this->email = $email;
    }

    public function name(): string
    {
        return 'user_signed_in';
    }

    public function payload(): array
    {
        return [
            'email' => $this->email->toString(),
        ];
    }
}

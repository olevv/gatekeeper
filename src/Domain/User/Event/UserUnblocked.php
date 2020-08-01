<?php

declare(strict_types=1);

namespace App\Domain\User\Event;

use App\Domain\Shared\Event\DomainEvent;
use App\Domain\User\ValueObject\Status;
use Ramsey\Uuid\UuidInterface;

final class UserUnblocked extends DomainEvent
{
    private Status $status;

    public function __construct(
        Status $status,
        UuidInterface $aggregateId,
        UuidInterface $eventId,
        \DateTimeImmutable $occurredOn
    ) {
        parent::__construct($aggregateId, $eventId, $occurredOn);
        $this->status = $status;
    }

    public function name(): string
    {
        return 'user_unblocked';
    }

    public function payload(): array
    {
        return [
            'status' => $this->status->toString(),
        ];
    }
}

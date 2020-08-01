<?php

declare(strict_types=1);

namespace App\Domain\User\Event;

use App\Domain\Shared\Event\DomainEvent;
use App\Domain\User\ValueObject\AccessToken;
use Ramsey\Uuid\UuidInterface;

final class UserAccessTokenUpdated extends DomainEvent
{
    private AccessToken $token;

    public function __construct(
        AccessToken $token,
        UuidInterface $aggregateId,
        UuidInterface $eventId,
        \DateTimeImmutable $occurredOn
    ) {
        parent::__construct($aggregateId, $eventId, $occurredOn);
        $this->token = $token;
    }

    public function name(): string
    {
        return 'user_access_token_updated';
    }

    public function payload(): array
    {
        return [
            'access_token' => $this->token->toString(),
            'expires_at' => $this->token->expiresAt() !== null
                ? $this->token->expiresAt()->format(\DateTimeInterface::ATOM)
                : null,
        ];
    }
}

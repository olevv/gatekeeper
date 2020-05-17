<?php

declare(strict_types=1);

namespace App\Application\Command\User\Unblock;

use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

final class UnblockCommand
{
    public UuidInterface $uuid;

    public function __construct(string $uuid)
    {
        $this->uuid = Uuid::fromString($uuid);
    }
}

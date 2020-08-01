<?php

declare(strict_types=1);

namespace App\Application\Command\User\Unblock;

use App\Application\Command\Command;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

final class UnblockCommand implements Command
{
    /** @psalm-readonly */
    public UuidInterface $uuid;

    public function __construct(string $uuid)
    {
        $this->uuid = Uuid::fromString($uuid);
    }
}

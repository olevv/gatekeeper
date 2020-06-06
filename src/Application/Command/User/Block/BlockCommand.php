<?php

declare(strict_types=1);

namespace App\Application\Command\User\Block;

use App\Infrastructure\Shared\Bus\Command\Command;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

final class BlockCommand implements Command
{
    /** @psalm-readonly */
    public UuidInterface $uuid;

    public function __construct(string $uuid)
    {
        $this->uuid = Uuid::fromString($uuid);
    }
}

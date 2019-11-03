<?php

declare(strict_types=1);

namespace App\Application\Command\User\Block;

use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

final class BlockCommand
{
    /**
     * @var UuidInterface
     */
    public $uuid;

    public function __construct(string $uuid)
    {
        $this->uuid = Uuid::fromString($uuid);
    }
}
